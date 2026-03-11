<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aforo extends Model
{
    protected $table = 'visitas.aforos';
    // protected $primaryKey = 'id_aforo';

    protected $fillable = [
        'sede_id',
        'area_id',
        'is_ingreso',
        'persona_id',
        // 'fecha_creacion',
        'usuario_marco_id',
        'persona_autoriza_id',
        'motivo',
    ];

    protected $casts = [
        'is_ingreso' => 'boolean',
        'fecha_creacion' => 'datetime',
    ];

    /**
     * Relación: El registro de aforo pertenece a una sede.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    /**
     * Relación: El registro de aforo pertenece a un área.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}