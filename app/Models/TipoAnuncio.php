<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoAnuncio extends Model
{
    protected $table = 'anuncios.tipo_anuncios';

    protected $fillable = [
        'descripcion',
    ];

    public function anuncios(): HasMany
    {
        return $this->hasMany(Anuncios::class, 'tipo_anuncio_id');
    }
}
