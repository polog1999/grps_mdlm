<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PCrgos extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.p_crgos';
    protected $primaryKey = 'cdgo_crgo';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_crgo',
        'cdgo_area',
        'de_crgo',
        'dc_crgo',
        'nu_orde',
        'co_usua_modi',
        'ts_usua_modi',
        'in_esta',
        'cdgo_crgo_prim',
        'in_nvel',
        'in_frma_dgtal',
    ];

    protected $casts = [
        'cdgo_crgo' => 'integer',
        'cdgo_area' => 'integer',
        'de_crgo' => 'string',
        'dc_crgo' => 'string',
        'nu_orde' => 'integer',
        'co_usua_modi' => 'integer',
        'ts_usua_modi' => 'datetime',
        'in_esta' => 'string',
        'cdgo_crgo_prim' => 'integer',
        'in_nvel' => 'string',
        'in_frma_dgtal' => 'integer',
    ];

    public function rCrgosLgins()
    {
        return $this->hasMany(RCrgosLgin::class, 'cdgo_crgo', 'cdgo_crgo');
    }

    public function pDtoTrmtes()
    {
        return $this->hasMany(PDtoTrmtes::class, 'cdgo_area', 'cdgo_area');
    }
}
