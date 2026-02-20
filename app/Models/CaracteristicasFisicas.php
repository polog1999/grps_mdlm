<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaracteristicasFisicas extends Model
{
    protected $table = 'anuncios.caracteristicas_fisicas';

    protected $fillable = [
        'descripcion',
    ];

    public function anuncios(): HasMany
    {
        return $this->hasMany(Anuncios::class, 'caracteristica_fisica_id');
    }
}
