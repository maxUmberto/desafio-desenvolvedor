<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHistoricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_historics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('source_currency_value', 10, 2);
            $table->string('source_currency_code');
            $table->string('source_currency_name');
            $table->decimal('destination_currency_bid_value', 10, 2);
            $table->string('destination_currency_code');
            $table->string('destination_currency_name');
            $table->decimal('destination_currency_total_bough_value', 10, 2);
            $table->decimal('payment_method_tax_value', 10, 2);
            $table->decimal('exchange_tax_value', 10, 2);
            $table->decimal('exchange_used_value', 10, 2);
            $table->foreignId('payment_method_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_historics');
    }
}
