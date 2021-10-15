<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

// models
use App\Models\UserHistoric;

// mails
use App\Mail\ExchangeCurrencyMail;

class SendExchangeCurrencyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user_historic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserHistoric $user_historic) {
        $this->user_historic = $user_historic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $user = $this->user_historic->user;

        Mail::to($user->email)->queue(new ExchangeCurrencyMail($this->user_historic));


        
    }
}
