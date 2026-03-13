<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnunciosColores extends Model
{
    protected $table = 'anuncios.anuncio_colores';

    protected $fillable = [
        'anuncio_id',
        'color_id',
    ];

    public function anuncio(): BelongsTo
    {
        return $this->belongsTo(Anuncios::class, 'anuncio_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Colores::class, 'color_id');
    }
}
