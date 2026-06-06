<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Auditoria
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $accion
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon $created_at
 */
class Auditoria extends Model
{
    protected $table = 'auditoria';

    public $timestamps = true;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'accion',
        'descripcion',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
