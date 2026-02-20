<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Materiales extends Model
{
    protected $table = 'anuncios.materiales';

    protected $fillable = [
        'descripcion',
    ];

    public function anuncios(): BelongsToMany
    {
        return $this->belongsToMany(
            Anuncios::class,
            'anuncios.anuncio_material',
            'material_id',
            'anuncio_id'
        );
    }
}
