<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;

class PaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reçu de Paiement - Académie de Langues')
                    ->view('emails.payment-receipt')
                    ->with([
                        'payment' => $this->payment,
                        'student' => $this->payment->registration->student,
                        'course' => $this->payment->registration->course,
                    ]);
    }
}