<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Models\Payment as PaymentModel;
use Illuminate\Queue\SerializesModels;

class Payment extends Mailable
{
    use Queueable, SerializesModels;
    protected $infoFromMail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PaymentModel $paymentData)
    {
        $this->infoFromMail = $paymentData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return 
        $this->from(auth()->user()->email)
        ->subject('Nuevo cobro generado')
        ->markdown('admin.mail.index',['cobro'=>$this->infoFromMail])
        ->attachFromStorageDisk('public', 'payment/'.$this->infoFromMail->id.'/'.$this->infoFromMail->adjunto,$this->infoFromMail->adjunto,[
            'mime' => 'application/pdf'
        ]);
    }
}
