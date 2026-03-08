<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformeActividad extends Model
{
    protected $table = 'teletrabajo.informes';

    protected $fillable = [
        'numero_informe',
        'usuario_id',
        'url_archivo',
        'fecha_subida',
    ];

    protected $casts = [
        'fecha_subida' => 'date',
    ];

    /**
     * Usuario que subió el informe.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
