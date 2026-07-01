<?php

namespace App\Mail;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscripcionEstadoCambiado extends Mailable
{
    use Queueable, SerializesModels;

    public $inscripcion;

    public function __construct(Inscripcion $inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    public function build()
    {
        $subject = match($this->inscripcion->estado) {
            'aprobado' => "Tu inscripción fue aprobada - Curso Preuniversitario UPEA",
            'rechazado' => "Tu inscripción fue rechazada - Curso Preuniversitario UPEA",
            'en_revision' => "Tu inscripción está en revisión - Curso Preuniversitario UPEA",
            default => "Estado de inscripción actualizado - Curso Preuniversitario UPEA"
        };

        return $this->view('emails.inscripcion_estado')
                    ->subject($subject);
    }
}
