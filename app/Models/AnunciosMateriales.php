<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnunciosMateriales extends Model
{
    protected $table = 'anuncios.anuncio_material';

    protected $fillable = [
        'anuncio_id',
        'material_id',
    ];

    public function anuncio(): BelongsTo
    {
        return $this->belongsTo(Anuncios::class, 'anuncio_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Materiales::class, 'material_id');
    }
}
