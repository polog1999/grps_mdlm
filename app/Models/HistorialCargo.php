<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialCargo extends Model
{
    protected $table = 'visitas.historial_cargos';
    protected $guarded = [];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
}
