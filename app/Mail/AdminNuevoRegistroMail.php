<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Aspirante;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNuevoRegistroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $aspirante;
    public $admin;

    public function __construct(Aspirante $aspirante, User $admin)
    {
        $this->aspirante = $aspirante;
        $this->admin = $admin;
    }

    public function build()
    {
        return $this->view('emails.admin_nuevo_registro')
                    ->subject("Nuevo aspirante registrado - UPEA");
    }
}
