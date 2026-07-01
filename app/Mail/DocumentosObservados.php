<?php

namespace App\Mail;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentosObservados extends Mailable
{
    use Queueable, SerializesModels;

    public $documento;
    public $observaciones;

    public function __construct(Documento $documento, $observaciones)
    {
        $this->documento = $documento;
        $this->observaciones = $observaciones;
    }

    public function build()
    {
        return $this->view('emails.documentos_observados')
                    ->subject("Documentos observados - Curso Preuniversitario UPEA");
    }
}
