<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    SoftDeletes
};

class PaymentMethod extends Model {
    
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'exchange_rate_percent'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function userHistoric() {
        return $this->hasMany(UserHistoric::class);
    }

}
