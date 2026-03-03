<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentosAnuncio extends Model
{
    protected $table = 'anuncios.documentos_anuncio';

    protected $primaryKey = 'id_documento';

    protected $fillable = [
        'anuncio_id',
        'tipo_documento',
        'n_documento',
        'fecha_emision',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];

    public function anuncio(): BelongsTo
    {
        return $this->belongsTo(Anuncios::class, 'anuncio_id');
    }
}
