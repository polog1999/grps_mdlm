<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ASmllaIntrno extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.a_smlla_intrno';
    protected $primaryKey = 'cdgo_smlla_intrno';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_smlla_intrno',
        'cdgo_area',
        'cdgo_anio',
        'cdgo_crgo',
        'cdgo_tpo_trmte',
        'nu_smlla_intrno',
        'co_usua_modi',
        'ts_usua_modi',
        'in_esta',
        'est_trmte',
        'pdf_firmado',
        'cdgo_dtos_ntrnos',
        'doc_referencia',
        'doc_asunto',
        'cdgo_area_original',
        'anula_moti',
        'cdgo_interno',
        'cdgo_externo',
        'cdgo_int_ext_item',
    ];

    protected $casts = [
        'cdgo_smlla_intrno' => 'integer',
        'cdgo_area' => 'integer',
        'cdgo_anio' => 'integer',
        'cdgo_crgo' => 'integer',
        'cdgo_tpo_trmte' => 'integer',
        'nu_smlla_intrno' => 'integer',
        'co_usua_modi' => 'integer',
        'ts_usua_modi' => 'datetime',
        'in_esta' => 'integer',
        'est_trmte' => 'integer',
        'pdf_firmado' => 'integer',
        'cdgo_dtos_ntrnos' => 'integer',
        'doc_referencia' => 'string',
        'doc_asunto' => 'string',
        'cdgo_area_original' => 'integer',
        'anula_moti' => 'string',
        'cdgo_interno' => 'integer',
        'cdgo_externo' => 'integer',
        'cdgo_int_ext_item' => 'integer',
    ];

    public function dtoNtrno()
    {
        return $this->belongsTo(DtoNtrnosGestrad::class, 'cdgo_dtos_ntrnos', 'cdgo_dtos_ntrnos');
    }
}
