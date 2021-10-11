<?php

namespace Tests\Feature\Exchange;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Enums\CurrencyMessages;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

// models
use App\Models\{
    PaymentMethod,
    User
};

class SimulateExchangeCurrencyTest extends TestCase {

    use DatabaseMigrations;
    
    public function testSimulateBuyWithCreditCard() {

        $user = User::factory()->create();
        $payment_method = PaymentMethod::where('name', 'boleto')->first();

        $fake_response = json_decode(file_get_contents('tests/Responses/response_currency_usd_brl.json'), true);

        Http::fake([
            env("AWESOME_API")."last/BRL-USD" => Http::response(
                $fake_response,
                200
            )
        ]);

        $response = $this->actingAs($user)
                        ->postJson('/api/exchange/simulate', [
                            'source_currency_code'      => 'BRL',
                            'destination_currency_code' => 'USD',
                            'source_currency_value'     => 5000,
                            'payment_method_id'         => $payment_method->id
                        ]);

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

        $response = json_decode($response->getContent())->data;

        \Log::info(json_encode($response));

        // $assert_destination_currency_bid_value = round($fake_response['USDBRL']['bid'], 2);
        // $assert_payment_method_tax_value = $this->calculatePaymentMethodTaxValue(5000, $payment_method);
        // $assert_exchange_tax_value = $this->calculateExchangeTaxValue(5000);
        // $assert_exchange_used_value = $this->calculateValueAfterTaxes(5000, $assert_payment_method_tax_value, $assert_exchange_tax_value);
        // $total_after_taxes = $this->calculateValueAfterTaxes(5000, $assert_payment_method_tax_value, $assert_exchange_tax_value);
        // $assert_destination_currency_total_bough_value = $this->calculateValueInDestinationCurrency($total_after_taxes, $assert_destination_currency_bid_value);

        // $this->assertEquals($response->source_currency_code, 'BRL');
        // $this->assertEquals($response->destination_currency_code, 'USD');
        // $this->assertEquals($response->source_currency_value, 5000);
        // $this->assertEquals($response->destination_currency_bid_value, $assert_destination_currency_bid_value);
        // $this->assertEquals($response->destination_currency_total_bough_value, $assert_destination_currency_total_bough_value);
        // $this->assertEquals($response->payment_method_tax_value, $assert_payment_method_tax_value);
        // $this->assertEquals($response->exchange_tax_value, $assert_exchange_tax_value);
        // $this->assertEquals($response->exchange_used_value, $assert_exchange_used_value);
        
        $this->assertEquals($response->source_currency_code, 'BRL');
        $this->assertEquals($response->destination_currency_code, 'USD');
        $this->assertEquals($response->source_currency_value, 5000);
        $this->assertEquals($response->destination_currency_bid_value, 5.51);
        $this->assertEquals($response->destination_currency_total_bough_value, 885.21);
        $this->assertEquals($response->payment_method_tax_value, 72.5);
        $this->assertEquals($response->exchange_tax_value, 50);
        $this->assertEquals($response->exchange_used_value, 4877.5);

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
