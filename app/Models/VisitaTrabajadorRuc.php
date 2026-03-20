<?php

namespace App\Models;

use App\Services\Sil\Personas\PersonaService;
use Illuminate\Database\Eloquent\Model;

class VisitaTrabajadorRuc extends Model
{
    protected $table = 'visitas.visitas_trabajadores_ruc';
    protected $guarded = [];
    public function persona()
{
    return $this->belongsTo(PersonaUno::class,'persona_id','id');
}
}
