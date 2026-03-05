<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Area extends Model
{
    protected $table = 'visitas.areas';
    // protected $primaryKey = 'id_area';

    protected $fillable = [
        'sede_id',
        'nombre',
        'parent_id',
        'nombre_corto',
        'orden',
        'estado'
    ];

    // Relación: Una Área pertenece a una Sede
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    // Relación: Una Área puede tener un área padre
    public function parentArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'parent_id', 'id');
    }
}