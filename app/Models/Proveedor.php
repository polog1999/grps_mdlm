<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $connection = 'mysql';
    protected $table = 'modulo_proveedores';
    protected $primaryKey = 'id_proveedor';
    protected $fillable = [
        'ruc','nombre','direccion'
    ];
    public $timestamps = false;
}
