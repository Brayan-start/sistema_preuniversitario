<?php

namespace App\Mail;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PagoRecibidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function build()
    {
        return $this->view('emails.pago_recibido')
                    ->subject("Comprobante de pago recibido - UPEA");
    }
}
