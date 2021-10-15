<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// models
use App\Models\UserHistoric;

class ExchangeCurrencyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user_historic;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserHistoric $user_historic) {
        $this->user_historic = $user_historic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ExchangeCurrencyMail')
                    ->subject('Consulta de conversão de valores')
                    ->with([
                        'username' => $this->user_historic->user->username,
                        'payment_method_name' => $this->user_historic->paymentMethod->name,
                        'user_historic' => $this->user_historic
                    ]);
    }
}
