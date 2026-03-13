<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RTrmtesNtrnos extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.r_trmtes_ntrnos';
    protected $primaryKey = 'cdgo_trmtes_ntrnos';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_trmtes_ntrnos',
        'cdgo_dtos_ntrnos',
        'cdgo_estdo_trmte',
        'cdgo_lgin',
        'cdgo_crgo_sa',
        'cdgo_crgo_en',
        'de_urls_agrdo',
        'fe_rcbdo',
        'de_obser_ntrnos',
        'in_copia',
        'in_esta',
        'cdgo_dtos_ntrnos_rfrncia',
        'nu_tram_todo_rfncia',
        'nu_tram_todo_rfncia_key',
        'cdgo_trmtes_ntrnos_rh',
        'cdgo_trmtes_ntrnos_rh_indice',
    ];

    protected $casts = [
        'cdgo_trmtes_ntrnos' => 'integer',
        'cdgo_dtos_ntrnos' => 'integer',
        'cdgo_estdo_trmte' => 'integer',
        'cdgo_lgin' => 'integer',
        'cdgo_crgo_sa' => 'integer',
        'cdgo_crgo_en' => 'integer',
        'de_urls_agrdo' => 'string',
        'fe_rcbdo' => 'datetime',
        'de_obser_ntrnos' => 'string',
        'in_copia' => 'string',
        'in_esta' => 'string',
        'cdgo_dtos_ntrnos_rfrncia' => 'integer',
        'nu_tram_todo_rfncia' => 'string',
        'nu_tram_todo_rfncia_key' => 'integer',
        'cdgo_trmtes_ntrnos_rh' => 'integer',
        'cdgo_trmtes_ntrnos_rh_indice' => 'integer',
    ];

    public function rCrgosLgin()
    {
        return $this->hasMany(RCrgosLgin::class, 'cdgo_crgo', 'cdgo_crgo_en');
    }
}
