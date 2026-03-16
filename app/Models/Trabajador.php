<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $connection = 'mysql';
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $guarded = [];

    public function area(){
        return $this->belongsTo(Area::class,'id_unidad_organica','id_unidad_organica');
    }
    public function cargo(){
        return $this->belongsTo(Cargo::class,'id_cargo','id_cargo');
    }
    public function regimen(){
        return $this->belongsTo(Regimen::class,'id_contratacion','id_contratacion');
    }

    // public function persona()
    // {
    //     return $this->belongsTo(PersonaUno::class);
    // }

    // public function regimen()
    // {
    //     return $this->belongsTo(Regimen::class);
    // }

    // public function clasificacion()
    // {
    //     return $this->belongsTo(Clasificacion::class);
    // }

    // public function historiales()
    // {
    //     return $this->hasMany(HistorialCargo::class);
    // }

    // // Relación para obtener el cargo que tiene HOY
    // public function cargoActual()
    // {
    //     return $this->hasOne(HistorialCargo::class)
    //         ->where('es_actual', true)
    //         ->whereNull('fecha_fin');
    // }
    // protected static function booted()
    // {
    //     static::deleting(function ($trabajador) {
    //         // Al borrar el trabajador, buscamos su persona y la eliminamos
    //         if ($trabajador->persona) {
    //             $trabajador->persona->delete();
    //         }

    //         // También es buen momento para limpiar el historial de cargos
    //         $trabajador->historiales()->delete();
    //     });
    // }
}
