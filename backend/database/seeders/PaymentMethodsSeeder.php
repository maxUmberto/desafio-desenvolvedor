<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $now = date('Y-m-d H:m:s');;

        DB::table('payment_methods')->insert([
            [
                'name'                  => 'boleto',
                'exchange_rate_percent' => 1.45,
                'created_at'            => $now,
                'updated_at'            => $now
            ],
            [
                'name'                  => 'cartao_de_credito',
                'exchange_rate_percent' => 7.63,
                'created_at'            => $now,
                'updated_at'            => $now
            ]
        ]);
    }
}
