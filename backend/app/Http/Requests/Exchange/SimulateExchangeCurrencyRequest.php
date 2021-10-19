<?php

namespace App\Http\Requests\Exchange;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CurrencyMessages;

class SimulateExchangeCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'source_currency_code'      => 'required|string|different:destination_currency_code',
            'destination_currency_code' => 'required|string|different:source_currency_code',
            'source_currency_value'     => 'required|numeric|min:1000|max:100000',
            'payment_method_id'         => 'required|exists:payment_methods,id'
        ];
    }

    public function messages() {
        return [
            'source_currency_code.different'      => CurrencyMessages::ERRORS['same_currency'],
            'destination_currency_code.different' => CurrencyMessages::ERRORS['same_currency'],
            'source_currency_value.min'           => CurrencyMessages::ERRORS['low_buy_value'],
            'source_currency_value.max'           => CurrencyMessages::ERRORS['high_buy_value'],
            'payment_method_id.exists'            => CurrencyMessages::ERRORS['invalid_paymenth_method']
        ];
    }
}
