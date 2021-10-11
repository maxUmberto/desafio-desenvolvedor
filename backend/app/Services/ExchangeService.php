<?php

namespace App\Services;

// requests
use App\Http\Requests\Exchange\SimulateExchangeCurrencyRequest;

// models
use App\Models\{
    PaymentMethod
};

class ExchangeService {

    public function convertCurrencyValue(SimulateExchangeCurrencyRequest $request, object $currency_quote) {

        $payment_method = PaymentMethod::where('id', $request->payment_method_id)->firstOrFail();

        $destination_currency_bid_value = round($currency_quote->bid, 2);

        $payment_method_tax_value = $this->calculatePaymentMethodTaxValue($request->source_currency_value, $payment_method);
        $exchange_tax_value = $this->calculateExchangeTaxValue($request->source_currency_value);
        $total_after_taxes = $this->calculateValueAfterTaxes($request->source_currency_value, $payment_method_tax_value, $exchange_tax_value);
        $destination_currency_total_bough_value = $this->calculateValueInDestinationCurrency($total_after_taxes, $destination_currency_bid_value);

        return [
            'source_currency_code'                   => $request->source_currency_code,
            'destination_currency_code'              => $request->destination_currency_code,
            'source_currency_value'                  => $request->source_currency_value,
            'paymenth_method'                        => $payment_method->name,
            'destination_currency_bid_value'         => $destination_currency_bid_value,
            'destination_currency_total_bough_value' => $destination_currency_total_bough_value,
            'payment_method_tax_value'               => $payment_method_tax_value,
            'exchange_tax_value'                     => $exchange_tax_value,
            'exchange_used_value'                    => $total_after_taxes
        ];

    }

    private function calculateValueAfterTaxes(Float $value, Float $payment_method_tax_value, Float $exchange_tax_value) {

        $total_after_taxes = $value - ($exchange_tax_value + $payment_method_tax_value);

        return round($total_after_taxes, 2);
    }

    private function calculateExchangeTaxValue(Float $value) {
        if($value > env('CONVERSION_VALUE')) {
            return round($value * env('ABOVE_CONVERSION_VALUE_TAX_VALUE'), 2);
        }

        return round($value * env('BELOW_CONVERSION_VALUE_TAX_VALUE'), 2);
    }

    private function calculatePaymentMethodTaxValue(Float $value, PaymentMethod $payment_method) {
        $payment_method_tax = ($payment_method->exchange_rate_percent / 100);

        return round($value * $payment_method_tax, 2);
    }

    private function calculateValueInDestinationCurrency(Float $value, Float $bid_value) {
        return round($value / $bid_value, 2);
    }

}