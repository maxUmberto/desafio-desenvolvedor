<?php

namespace Tests\Feature\Exchange;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Enums\CurrencyMessages;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

// models
use App\Models\{
    PaymentMethod,
    User
};

// jobs 
use App\Jobs\SendExchangeCurrencyEmailJob;

class SimulateExchangeCurrencyTest extends TestCase {

    use DatabaseMigrations;
    
    public function testSimulateBuyWithBoleto() {

        Bus::fake();

        $user = User::factory()->create();
        $payment_method = PaymentMethod::where('name', 'boleto')->first();

        $this->simulateEndpointRequest();
        $source_currency_value = rand(1000, 100000);

        $response = $this->actingAs($user)
                        ->postJson('/api/exchange/simulate', [
                            'source_currency_code'      => 'BRL',
                            'destination_currency_code' => 'USD',
                            'source_currency_value'     => $source_currency_value,
                            'payment_method_id'         => $payment_method->id
                        ]);

        $this->assertResponseJson($response, $user, $payment_method);

        $response = json_decode($response->getContent())->data;

        $this->assertReturnValues($response, $source_currency_value, $payment_method);

    }
    
    public function testSimulateBuyWithCreditCard() {

        Bus::fake();

        $user = User::factory()->create();
        $payment_method = PaymentMethod::where('name', 'cartao_de_credito')->first();

        $this->simulateEndpointRequest();
        $source_currency_value = rand(1000, 100000);

        $response = $this->actingAs($user)
                        ->postJson('/api/exchange/simulate', [
                            'source_currency_code'      => 'BRL',
                            'destination_currency_code' => 'USD',
                            'source_currency_value'     => $source_currency_value,
                            'payment_method_id'         => $payment_method->id
                        ]);

        $this->assertResponseJson($response, $user, $payment_method);

        $response = json_decode($response->getContent())->data;

        $this->assertReturnValues($response, $source_currency_value, $payment_method);

    }

    private function assertResponseJson(\Illuminate\Testing\TestResponse $response, User $user, PaymentMethod $payment_method) {
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'source_currency_code',
                    'destination_currency_code',
                    'source_currency_value',
                    'paymenth_method',
                    'destination_currency_bid_value',
                    'destination_currency_total_bough_value',
                    'payment_method_tax_value',
                    'exchange_tax_value',
                    'exchange_used_value'
                ]
            ]);

        Bus::assertDispatched(SendExchangeCurrencyEmailJob::class);

        $response = json_decode($response->getContent())->data;

        $this->assertDatabaseHas('users_historics', [
            'user_id' => $user->id,
            'source_currency_value' => $response->source_currency_value,
            'source_currency_code' => 'BRL',
            'source_currency_name' => 'Real Brasileiro',
            'destination_currency_bid_value' => $response->destination_currency_bid_value,
            'destination_currency_code' => 'USD',
            'destination_currency_name' => 'Dólar Americano',
            'destination_currency_total_bough_value' => $response->destination_currency_total_bough_value,
            'payment_method_tax_value' => $response->payment_method_tax_value,
            'exchange_tax_value' => $response->exchange_tax_value,
            'exchange_used_value' => $response->exchange_used_value,
            'payment_method_id' => $payment_method->id
        ]);
    }

    private function assertReturnValues(Object $response, Float $source_currency_value, PaymentMethod $payment_method) {

        $bid_value = $this->getBidValue();

        $assert_exchange_tax_value = $this->calculateExchangeTaxValue($source_currency_value);
        $assert_payment_method_tax_value = $this->calculatePaymentMethodTaxValue($source_currency_value, $payment_method);
        $assert_exchange_used_value = $this->calculateValueAfterTaxes($source_currency_value, $assert_payment_method_tax_value, $assert_exchange_tax_value);
        $assert_destination_currency_total_bough_value = $this->calculateValueInDestinationCurrency($assert_exchange_used_value, $bid_value);
        
        $this->assertEquals($response->source_currency_code, 'BRL');
        $this->assertEquals($response->destination_currency_code, 'USD');
        $this->assertEquals($response->source_currency_value, $source_currency_value);
        $this->assertEquals($response->destination_currency_bid_value, $bid_value);
        $this->assertEquals($response->destination_currency_total_bough_value, $assert_destination_currency_total_bough_value);
        $this->assertEquals($response->payment_method_tax_value, $assert_payment_method_tax_value);
        $this->assertEquals($response->exchange_tax_value, $assert_exchange_tax_value);
        $this->assertEquals($response->exchange_used_value, $assert_exchange_used_value);
    }

    private function getBidValue() {
        $fake_response = json_decode(file_get_contents('tests/Responses/response_currency_usd_brl.json'), true);

        return round($fake_response['USDBRL']['bid'], 2);
    }

    private function simulateEndpointRequest() {

        $fake_response = json_decode(file_get_contents('tests/Responses/response_currency_usd_brl.json'), true);

        Http::fake([
            env("AWESOME_API")."last/BRL-USD" => Http::response(
                $fake_response,
                200
            )
        ]);

        return $fake_response;

    }

    private function calculatePaymentMethodTaxValue(Float $value, PaymentMethod $payment_method) {
        $payment_method_tax = ($payment_method->exchange_rate_percent / 100);

        return round($value * $payment_method_tax, 2);
    }

    private function calculateExchangeTaxValue(Float $value) {
        if($value > env('CONVERSION_VALUE')) {
            return round($value * env('ABOVE_CONVERSION_VALUE_TAX_VALUE'), 2);
        }

        return round($value * env('BELOW_CONVERSION_VALUE_TAX_VALUE'), 2);
    }

    private function calculateValueAfterTaxes(Float $value, Float $payment_method_tax_value, Float $exchange_tax_value) {

        $total_after_taxes = $value - ($exchange_tax_value + $payment_method_tax_value);

        return round($total_after_taxes, 2);
    }

    private function calculateValueInDestinationCurrency(Float $value, Float $bid_value) {
        return round($value / $bid_value, 2);
    }

}
