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
            $table->unsignedInteger('user_id')->constrained();
            $table->integer('source_currency_value');
            $table->string('source_currency_code');
            $table->string('source_currency_name');
            $table->integer('destination_currency_bid_value');
            $table->string('destination_currency_code');
            $table->string('destination_currency_name');
            $table->integer('destination_currency_total_bough_value');
            $table->integer('payment_rate_value');
            $table->integer('exchange_rate_value');
            $table->integer('exchange_used_value');
            $table->decimal('exchange_rate_percent', 5, 2);
            $table->unsignedInteger('payment_method_id')->constrained();
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
        Schema::dropIfExists('user_historics');
    }
}
