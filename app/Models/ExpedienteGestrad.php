<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpedienteGestrad extends Model
{
    protected $connection = 'pgsql_qr';
    protected $table = 'qr.codigoqr';

}