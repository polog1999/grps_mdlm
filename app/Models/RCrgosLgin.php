<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RCrgosLgin extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.r_crgos_lgin';
    protected $primaryKey = 'cdgo_crgo_lgin';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_crgo_lgin',
        'cdgo_lgin',
        'cdgo_crgo',
        'ts_usua_modi',
        'in_esta',
        'in_crgo_prmro',
    ];

    protected $casts = [
        'cdgo_crgo_lgin' => 'integer',
        'cdgo_lgin' => 'integer',
        'cdgo_crgo' => 'integer',
        'ts_usua_modi' => 'datetime',
        'in_esta' => 'string',
        'in_crgo_prmro' => 'integer',
    ];

    public function pLgin()
    {
        return $this->hasOne(PLgin::class, 'cdgo_lgin', 'cdgo_lgin');
    }

    public function pCrgo()
    {
        return $this->belongsTo(PCrgos::class, 'cdgo_crgo', 'cdgo_crgo');
    }

}
