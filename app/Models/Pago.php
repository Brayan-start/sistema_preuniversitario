<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pago
 * @package App\Models
 * @property int $id
 * @property int $inscripcion_id
 * @property string $numero_comprobante
 * @property string $comprobante_path
 * @property float $monto
 * @property string $fecha_pago
 * @property string $estado
 * @property string $motivo_rechazo
 * @property int $admin_id
 */
class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'inscripcion_id',
        'numero_comprobante',
        'comprobante_path',
        'monto',
        'fecha_pago',
        'estado',
        'motivo_rechazo',
        'admin_id',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
