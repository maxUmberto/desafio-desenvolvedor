<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model
};

class UserHistoric extends Model {
    
    use HasFactory;

    protected $table = 'users_historics';

    protected $fillable = [
        'user_id',
        'source_currency_value',
        'source_currency_code',
        'source_currency_name',
        'destination_currency_bid_value',
        'destination_currency_code',
        'destination_currency_name',
        'destination_currency_total_bough_value',
        'payment_method_tax_value',
        'exchange_tax_value',
        'exchange_used_value',
        'exchange_rate_percent',
        'payment_method_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class);
    }
}
