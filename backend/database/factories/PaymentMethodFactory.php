<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'                  => $this->faker->word(),
            'exchange_rate_percent' => $this->faker->randomFloat(2, 1, 100)
        ];
    }

    public function billet() {
        return $this->state(function (array $attributes) {
            return [
                'name'                  => 'boleto',
                'exchange_rate_percent' => 1.45
            ];
        });
    }
    
    public function credit_card() {
        return $this->state(function (array $attributes) {
            return [
                'name'                  => 'cartao_credito',
                'exchange_rate_percent' => 7.63
            ];
        });
    }
}
