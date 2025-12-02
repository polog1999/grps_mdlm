<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TipoEstadoLicencia extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'estadolicencia';
    protected $primaryKey = 'esl_id';
    public $timestamps = false;
    protected $fillable = [
        'esl_descripcion',
        'esl_activo',
        'esl_filaoriginal',
        'esl_filaeliminada',
        'usa_id',
    ];
}