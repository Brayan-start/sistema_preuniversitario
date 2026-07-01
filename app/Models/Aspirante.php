<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Aspirante
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $nombre_completo
 * @property string $ci
 * @property string $correo
 * @property string $celular
 * @property string $colegio_procedencia
 * @property int $anio_egreso
 */
class Aspirante extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombre_completo',
        'ci',
        'correo',
        'celular',
        'colegio_procedencia',
        'anio_egreso',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
