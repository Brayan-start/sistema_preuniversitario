<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Curso
 * @package App\Models
 * @property int $id
 * @property string $nombre_curso
 * @property string $descripcion
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property float $monto_arancel
 * @property int $cupos_disponibles
 * @property string $requisitos
 * @property bool $is_active
 */
class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_curso',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'monto_arancel',
        'cupos_disponibles',
        'requisitos',
        'horario',
        'is_active',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'monto_arancel' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
