<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Documento
 * @package App\Models
 * @property int $id
 * @property int $aspirante_id
 * @property int $inscripcion_id
 * @property string $tipo
 * @property string $archivo_path
 * @property string $formato
 * @property string $estado
 */
class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'aspirante_id',
        'inscripcion_id',
        'tipo',
        'archivo_path',
        'formato',
        'estado',
    ];

    public function aspirante()
    {
        return $this->belongsTo(Aspirante::class);
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }
}
