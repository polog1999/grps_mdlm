<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AAnios extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.a_anios';
    protected $primaryKey = 'cdgo_anio';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_anio',
        'de_anio',
        'dc_anio',
        'cdgo_cmpnia',
        'no_anio_fscal',
        'nu_mnto_uit',
    ];

    protected $casts = [
        'cdgo_anio' => 'integer',
        'de_anio' => 'integer',
        'dc_anio' => 'string',
        'cdgo_cmpnia' => 'integer',
        'no_anio_fscal' => 'string',
        'nu_mnto_uit' => 'decimal:2',
    ];

    public function dtoNtrnos()
    {
        return $this->hasMany(DtoNtrnosGestrad::class, 'cdgo_anio', 'cdgo_anio');
    }

    public function pDtoTrmtes()
    {
        return $this->hasMany(PDtoTrmtes::class, 'cdgo_anio', 'cdgo_anio');
    }
}
