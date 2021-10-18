<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;

class AwesomeApi {

    private $client;

    public function __construct(GuzzleClient $guzzle_client) {
        $this->client = new $guzzle_client([
            'base_uri' => env('AWESOME_API')
        ]);
    }

    public function getCurrencyQuote(String $source_currency, String $destination_currency) {

        $currendy_string = "{$destination_currency}{$source_currency}";

        $response = $this->client->request('GET', "/last/{$destination_currency}-{$source_currency}");

        return json_decode($response->getBody())->$currendy_string;
    }

    public function getAvailableCurrencies() {
        $response = $this->client->request('GET', "/available/uniq");

        $response = json_decode($response->getBody());

        return $this->formatAvailableCurrenciesReturn($response);
    }

    private function formatAvailableCurrenciesReturn(object $currencies) {
        $currencies_array = [];
        $currencies = get_object_vars($currencies);
        
        foreach($currencies as $currency_code => $currency_name) {
            array_push($currencies_array, [
                'currency_code' => $currency_code,
                'currency_name' => $currency_name
            ]);
        }

        sort($currencies_array);

        return $currencies_array;
    }
    
}