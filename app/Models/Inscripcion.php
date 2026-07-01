<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inscripcion
 * @package App\Models
 * @property int $id
 * @property int $aspirante_id
 * @property int $curso_id
 * @property string $estado
 * @property string $grupo
 * @property string $motivo_rechazo
 * @property string $fecha_cambio_estado
 * @property int $admin_responsable_id
 */
class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'aspirante_id',
        'curso_id',
        'estado',
        'grupo',
        'motivo_rechazo',
        'fecha_cambio_estado',
        'admin_responsable_id',
    ];

    protected $casts = [
        'fecha_cambio_estado' => 'datetime',
    ];

    public function aspirante()
    {
        return $this->belongsTo(Aspirante::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function adminResponsable()
    {
        return $this->belongsTo(User::class, 'admin_responsable_id');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
