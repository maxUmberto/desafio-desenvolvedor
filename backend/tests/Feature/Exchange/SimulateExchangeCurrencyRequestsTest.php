<?php

namespace Tests\Feature\Exchange;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Enums\CurrencyMessages;
use Tests\TestCase;

// models
use App\Models\{
    PaymentMethod,
    User
};

class SimulateExchangeCurrencyRequestsTest extends TestCase {

    use DatabaseMigrations;
    
    public function testCantExchangeFromTheSameCurrency() {

        $user = User::factory()->create();
        $payment_method = PaymentMethod::inRandomOrder()->first();

        $response = $this->actingAs($user)
                         ->postJson('/api/exchange/simulate', [
                            'source_currency_code' => 'BRL',
                            'destination_currency_code' => 'BRL',
                            'source_currency_value' => rand(1000, 100000),
                            'payment_method_id' => $payment_method->id
                         ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'destination_currency_code' => CurrencyMessages::ERRORS['same_currency']
                ]);

    }

    public function testCantExchangeLowValue() {
        $user = User::factory()->create();
        $payment_method = PaymentMethod::inRandomOrder()->first();

        $response = $this->actingAs($user)
                         ->postJson('/api/exchange/simulate', [
                            'source_currency_code' => 'BRL',
                            'destination_currency_code' => 'USD',
                            'source_currency_value' => rand(1, 999),
                            'payment_method_id' => $payment_method->id
                         ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'source_currency_value' => CurrencyMessages::ERRORS['low_buy_value']
                ]);
    }

    public function testCantExchangeHighValue() {
        $user = User::factory()->create();
        $payment_method = PaymentMethod::inRandomOrder()->first();

        $response = $this->actingAs($user)
                         ->postJson('/api/exchange/simulate', [
                            'source_currency_code' => 'BRL',
                            'destination_currency_code' => 'USD',
                            'source_currency_value' => rand(100001, 999999999),
                            'payment_method_id' => $payment_method->id
                         ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'source_currency_value' => CurrencyMessages::ERRORS['high_buy_value']
                ]);
    }

    public function testCantExchangeWithInvalidPaymentMethod() {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson('/api/exchange/simulate', [
                            'source_currency_code' => 'BRL',
                            'destination_currency_code' => 'USD',
                            'source_currency_value' => rand(1, 100000),
                            'payment_method_id' => rand(100, 150)
                        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'payment_method_id' => CurrencyMessages::ERRORS['invalid_paymenth_method']
                ]);
    }

}
