<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PUsrios extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.p_usrios';
    protected $primaryKey = 'cdgo_usrios';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_usrios',
        'cdgo_usrios_prim',
        'no_crto',
        'de_dire',
        'nu_tele',
        'co_ubig',
        'de_mail',
        'nu_docu',
        'de_ocup_rubr',
        'in_tipo_pers',
        'in_esta',
        'fe_inic_acti',
        'de_anex',
        'nu_ruc',
        'de_foto',
        'de_dire_ral',
        'in_tpo_ncionldad',
        'nu_part',
        'cdgo_nmbre_via',
        'no_crto_sstma',
        'de_drccon_intror',
        'de_drccon_dprtmnto',
        'de_drccon_ltra',
        'de_drccon_mnzna',
        'de_drccon_lte',
        'de_drccon_agrpcon',
        'de_drccon_rfrnca',
        'de_drccon_cdra',
        'codcontri',
    ];

    protected $casts = [
        'cdgo_usrios' => 'integer',
        'cdgo_usrios_prim' => 'integer',
        'no_crto' => 'string',
        'de_dire' => 'string',
        'nu_tele' => 'string',
        'co_ubig' => 'string',
        'de_mail' => 'string',
        'nu_docu' => 'string',
        'de_ocup_rubr' => 'string',
        'in_tipo_pers' => 'string',
        'in_esta' => 'string',
        'fe_inic_acti' => 'date',
        'de_anex' => 'integer',
        'nu_ruc' => 'string',
        'de_foto' => 'string',
        'de_dire_ral' => 'string',
        'in_tpo_ncionldad' => 'string',
        'nu_part' => 'string',
        'cdgo_nmbre_via' => 'integer',
        'no_crto_sstma' => 'string',
        'de_drccon_intror' => 'string',
        'de_drccon_dprtmnto' => 'string',
        'de_drccon_ltra' => 'string',
        'de_drccon_mnzna' => 'string',
        'de_drccon_lte' => 'string',
        'de_drccon_agrpcon' => 'string',
        'de_drccon_rfrnca' => 'string',
        'de_drccon_cdra' => 'string',
        'codcontri' => 'string',
    ];

    public function pLgin()
    {
        return $this->hasOne(PLgin::class, 'cdgo_usrios', 'cdgo_usrios');
    }
}
