<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DtoNtrnosGestrad extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.p_dtos_ntrnos';
    protected $primaryKey = 'cdgo_dtos_ntrnos';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_dtos_ntrnos',
        'cdgo_tpo_trmte',
        'cdgo_anio',
        'cdgo_area',
        'cdgo_lgin',
        'nu_tram_todo',
        'nu_tram',
        'de_obser',
        'de_urls_adjnto',
        'fe_ingrso',
        'in_esta',
        'cdgo_cmpnia',
        'cdgo_dto_trmte',
        'cdgo_dtos_ntrnos_pri',
        'in_envo_fsco',
        'nu_tram_cmplto',
        'in_prrdad_trmte',
        'nu_tram_todo_rfncia',
        'nu_tram_todo_rfncia_key',
        'res_cod',
    ];

    protected $casts = [
        'cdgo_dtos_ntrnos' => 'integer',
        'cdgo_tpo_trmte' => 'integer',
        'cdgo_anio' => 'integer',
        'cdgo_area' => 'integer',
        'cdgo_lgin' => 'integer',
        'nu_tram_todo' => 'string',
        'nu_tram' => 'integer',
        'de_obser' => 'string',
        'de_urls_adjnto' => 'string',
        'fe_ingrso' => 'datetime',
        'in_esta' => 'string',
        'cdgo_cmpnia' => 'integer',
        'cdgo_dto_trmte' => 'integer',
        'cdgo_dtos_ntrnos_pri' => 'integer',
        'in_envo_fsco' => 'integer',
        'nu_tram_cmplto' => 'string',
        'in_prrdad_trmte' => 'integer',
        'nu_tram_todo_rfncia' => 'string',
        'nu_tram_todo_rfncia_key' => 'integer',
        'res_cod' => 'string',
    ];

    public function aSmllaIntrnos()
    {
        return $this->hasMany(ASmllaIntrno::class, 'cdgo_dtos_ntrnos', 'cdgo_dtos_ntrnos');
    }

    public function rTrmtesNtrnos()
    {
        return $this->hasMany(RTrmtesNtrnos::class, 'cdgo_dtos_ntrnos', 'cdgo_dtos_ntrnos');
    }

    public function pLgin()
    {
        return $this->belongsTo(PLgin::class, 'cdgo_lgin', 'cdgo_lgin');
    }

    public function pDtoTrmte()
    {
        return $this->belongsTo(PDtoTrmtes::class, 'cdgo_dto_trmte', 'cdgo_dto_trmte');
    }

    public function aAnio()
    {
        return $this->belongsTo(AAnios::class, 'cdgo_anio', 'cdgo_anio');
    }
}
