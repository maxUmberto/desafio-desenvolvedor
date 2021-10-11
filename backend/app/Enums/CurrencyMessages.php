<?php

namespace App\Enums;

abstract class CurrencyMessages {
    
    const ERRORS = [
        'high_buy_value' => 'O valor máximo para compra é R$100.000,00',
        'low_buy_value' => 'O valor mínimo de compra é R$1000,00',
        'same_currency' => 'Moeda de origem não pode ser a mesma que a moeda de compra',
        'invalid_paymenth_method' => 'O método de pagamento escolhido é inválido'
    ];
}