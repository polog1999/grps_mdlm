<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regimen extends Model
{
    protected $table = 'visitas.regimenes';
    protected $fillable = [
        'parent_id',
        'cregimen',
        'de_regimen',
        'estado',
        // 'fecha_creacion',
        'user_id_creo',
        'user_id_modi',
        'created_at',
        'updated_at'
    ];
     public function parentRegimen(): BelongsTo
    {
        return $this->belongsTo(Regimen::class, 'parent_id', 'id');
    }
}
