<?php

namespace App\Services;

// requests
use App\Http\Requests\Exchange\SimulateExchangeCurrencyRequest;

// models
use App\Models\{
    PaymentMethod,
    UserHistoric
};

class UserHistoricService {

    public function saveUserHistoric(SimulateExchangeCurrencyRequest $request, Array $exchange_info, Object $currency_quote) {

        $payment_method = PaymentMethod::where('id', $request->payment_method_id)->firstOrFail();

        $currency_name = explode('/', $currency_quote->name);
        $destination_currency_name = $currency_name[0];
        $source_currency_name = $currency_name[1];

        UserHistoric::create([
            'user_id'                                => auth()->user()->id,
            'source_currency_value'                  => $request->source_currency_value,
            'source_currency_code'                   => $request->source_currency_code,
            'source_currency_name'                   => $source_currency_name,
            'destination_currency_bid_value'         => round($currency_quote->bid, 2),
            'destination_currency_code'              => $request->destination_currency_code,
            'destination_currency_name'              => $destination_currency_name,
            'destination_currency_total_bough_value' => $exchange_info['destination_currency_total_bough_value'],
            'payment_method_tax_value'               => $exchange_info['payment_method_tax_value'],
            'exchange_tax_value'                     => $exchange_info['exchange_tax_value'],
            'exchange_used_value'                    => $exchange_info['exchange_used_value'],
            'payment_method_id'                      => $payment_method->id
        ]);

    }

}