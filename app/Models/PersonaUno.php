<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaUno extends Model
{
    protected $table='visitas.personas';
    protected $guarded = [];

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id','id_tipo_documento');
    }

    public function trabajador()
    {
        return $this->hasOne(Trabajador::class, 'persona_id');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    // Accesor para mostrar "Nombre Apellido" en los selects de Filament
    public function getFullNombreAttribute()
    {
        return "{$this->nombres} {$this->apellido_paterno}";
    }
}
