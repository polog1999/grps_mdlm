<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoLicencia extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'tipolicencia';
    protected $primaryKey = 'tli_id';
    public $timestamps = true;

    protected $fillable = [
        'tli_descripcion',
        'tli_filaoriginal',
        'tli_filaeliminada',
        'tli_activo',
    ];


}