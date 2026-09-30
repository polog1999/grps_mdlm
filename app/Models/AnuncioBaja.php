<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnuncioBaja extends Model
{
    protected $table =  'anuncios.anuncios_baja';

    public function anuncio(){
        return $this->belongsTo(Anuncios::class);
    }
    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }
}