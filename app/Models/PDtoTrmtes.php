<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDtoTrmtes extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.p_dto_trmtes';
    protected $primaryKey = 'cdgo_dto_trmte';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_dto_trmte',
        'cdgo_tpo_trmte',
        'cdgo_docmnto_trmte',
        'cdgo_usrios',
        'cdgo_anio',
        'cdgo_area',
        'cdgo_mdlo',
        'nu_expe_todo',
        'nu_expe_trmte',
        'nu_docu_trmte',
        'nu_foli_trmte',
        'fe_ingr_trmte',
        'fe_limi_trmte',
        'de_obse_trmte',
        'in_sile_admi',
        'in_encu_tram',
        'in_web',
        'de_obse_todo',
        'co_usua_modi',
        'co_usua_ingr',
        'in_docu_trmte',
        'cdgo_cmpnia',
        'cdgo_usrios_rprsntnte',
        'cdgo_usrios_crreo',
        'nu_tiem_sile_adcnal',
        'ts_usua_modi',
        'cdgo_frma_pgo',
        'cdgo_rqto_trmte',
        'codcat',
        'cgonumero',
        'cgomontota',
        'bas_num',
        'bas_tpo',
        'exp_unfcado',
        'bas_origen',
        'sub_docmnto_trmte',
    ];

    protected $casts = [
        'cdgo_dto_trmte' => 'integer',
        'cdgo_tpo_trmte' => 'integer',
        'cdgo_docmnto_trmte' => 'integer',
        'cdgo_usrios' => 'integer',
        'cdgo_anio' => 'integer',
        'cdgo_area' => 'integer',
        'cdgo_mdlo' => 'integer',
        'nu_expe_todo' => 'string',
        'nu_expe_trmte' => 'integer',
        'nu_docu_trmte' => 'string',
        'nu_foli_trmte' => 'integer',
        'fe_ingr_trmte' => 'datetime',
        'fe_limi_trmte' => 'datetime',
        'de_obse_trmte' => 'string',
        'in_sile_admi' => 'string',
        'in_encu_tram' => 'string',
        'in_web' => 'string',
        'de_obse_todo' => 'string',
        'co_usua_modi' => 'integer',
        'co_usua_ingr' => 'integer',
        'in_docu_trmte' => 'string',
        'cdgo_cmpnia' => 'integer',
        'cdgo_usrios_rprsntnte' => 'integer',
        'cdgo_usrios_crreo' => 'integer',
        'nu_tiem_sile_adcnal' => 'integer',
        'ts_usua_modi' => 'datetime',
        'cdgo_frma_pgo' => 'integer',
        'cdgo_rqto_trmte' => 'integer',
        'codcat' => 'string',
        'cgonumero' => 'string',
        'cgomontota' => 'decimal:2',
        'bas_num' => 'integer',
        'bas_tpo' => 'string',
        'exp_unfcado' => 'string',
        'bas_origen' => 'string',
        'sub_docmnto_trmte' => 'integer',
    ];

    public function dtoNtrnos()
    {
        return $this->hasMany(DtoNtrnosGestrad::class, 'cdgo_dto_trmte', 'cdgo_dto_trmte');
    }

    public function pUsrio()
    {
        return $this->belongsTo(PUsrios::class, 'cdgo_usrios', 'cdgo_usrios');
    }

    public function aAnio()
    {
        return $this->belongsTo(AAnios::class, 'cdgo_anio', 'cdgo_anio');
    }

    public function pCrgos()
    {
        return $this->hasMany(PCrgos::class, 'cdgo_area', 'cdgo_area');
    }
}
