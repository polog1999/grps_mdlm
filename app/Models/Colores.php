<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Colores extends Model
{
    protected $table = 'anuncios.colores';

    protected $fillable = [
        'descripcion',
    ];

    public function anuncios(): BelongsToMany
    {
        return $this->belongsToMany(
            Anuncios::class,
            'anuncios.anuncio_colores',
            'color_id',
            'anuncio_id'
        );
    }
}
