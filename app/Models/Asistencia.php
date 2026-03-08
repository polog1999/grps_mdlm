<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'teletrabajo.asistencias';

    protected $fillable = [
        'usuario_id',
        'hora_entrada',
        'hora_salida',
        'hora_almuerzo_salida',
        'hora_almuerzo_entrada',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime',
        'hora_almuerzo_salida' => 'datetime',
        'hora_almuerzo_entrada' => 'datetime',
    ];

    /**
     * Usuario que registró la asistencia.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
