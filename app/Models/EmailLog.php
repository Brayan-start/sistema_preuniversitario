<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'destinatario',
        'asunto',
        'tipo_evento',
        'estado_envio',
        'mensaje_error',
        'usuario_responsable_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'usuario_responsable_id');
    }
}
