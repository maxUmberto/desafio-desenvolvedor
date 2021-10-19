<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// models
use App\Models\UserHistoric;

class HistoricController extends Controller {
    
    public function getUserHistoric() {
        $historic = UserHistoric::with('paymentMethod')
                ->where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json([
            'data' => [
                'user_historic' => $historic
            ]
            ], 200);
    }

}
