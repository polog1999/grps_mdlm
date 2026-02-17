<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = 'visitas.trabajadores';
    protected $guarded = [];

    public function persona()
    {
        return $this->belongsTo(PersonaUno::class);
    }

    public function regimen()
    {
        return $this->belongsTo(Regimen::class);
    }

    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    public function historiales()
    {
        return $this->hasMany(HistorialCargo::class);
    }

    // Relación para obtener el cargo que tiene HOY
    public function cargoActual()
    {
        return $this->hasOne(HistorialCargo::class)
            ->where('es_actual', true)
            ->whereNull('fecha_fin');
    }
    protected static function booted()
{
    static::deleting(function ($trabajador) {
        // Al borrar el trabajador, buscamos su persona y la eliminamos
        if ($trabajador->persona) {
            $trabajador->persona->delete();
        }
        
        // También es buen momento para limpiar el historial de cargos
        $trabajador->historiales()->delete();
    });
}
}
