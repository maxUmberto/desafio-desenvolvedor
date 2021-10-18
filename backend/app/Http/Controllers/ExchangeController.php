<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// requests
use App\Http\Requests\Exchange\SimulateExchangeCurrencyRequest;

// services
use App\Services\{
    AwesomeApi,
    ExchangeService,
    UserHistoricService
};

// jobs
use App\Jobs\SendExchangeCurrencyEmailJob;

class ExchangeController extends Controller {

    private $awesome_api;
    private $exchange_service;
    private $user_historic_service;

    public function __construct(AwesomeApi $awesome_api, ExchangeService $exchange_service, UserHistoricService $user_historic_service) {
        $this->awesome_api = $awesome_api;
        $this->exchange_service = $exchange_service;
        $this->user_historic_service = $user_historic_service;
    }
    
    public function simulateExchangeCurrency(SimulateExchangeCurrencyRequest $request) {

        $currency_quote = $this->awesome_api->getCurrencyQuote($request->source_currency_code, $request->destination_currency_code);
        $exchange_info = $this->exchange_service->convertCurrencyValue($request, $currency_quote);

        $user_historic = $this->user_historic_service->saveUserHistoric($request, $exchange_info, $currency_quote);

        SendExchangeCurrencyEmailJob::dispatch($user_historic);

        return response()->json([
            'success' => true,
            'data'    => $exchange_info
        ], 200);

    }

    public function getAvailableCurrencies() {
        $available_currencies = $currency_quote = $this->awesome_api->getAvailableCurrencies();

        return response()->json([
            'data' => [
                'available_currencies' => $available_currencies
            ]
        ], 200);
    }

}
