<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     * Incluimos el esquema (ejemplo: 'dbo') seguido del nombre de la tabla.
     * * @var string
     */
    protected $table = 'visitas.sedes';

    /**
     * La llave primaria asociada a la tabla.
     * En tu caso, según el modelo de Navicat, es 'id_sede'.
     * * @var string
     */
    // protected $primaryKey = 'id_sede';

    /**
     * Los atributos que se pueden asignar de manera masiva.
     * * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'aforo',
        'estado',
        'user_id_creo',
        'user_id_modi'
    ];

    /**
     * Los atributos que deben ser casteados a tipos específicos.
     * Esto ayuda a que Laravel trate 'aforo' como entero y 'estado' según necesites.
     * * @var array<string, string>
     */
    protected $casts = [
        'aforo' => 'integer',
        'estado' => 'integer',
    ];
    
    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function historiales()
    {
        return $this->hasMany(HistorialCargo::class);
    }
}