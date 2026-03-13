<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PLgin extends Model
{
    protected $connection = 'pgsql_gestrad';

    protected $table = 'sistema.p_lgin';
    protected $primaryKey = 'cdgo_lgin';
    public $timestamps = false;

    protected $fillable = [
        'cdgo_lgin',
        'cdgo_prfle',
        'cdgo_crgo',
        'cdgo_usrios',
        'de_lgin',
        'de_pass',
        'ts_usua_modi',
        'co_usua_modi',
        'in_esta',
        'de_urls_frma',
        'cdgo_sigex',
        'lgin_sigex',
        'tpocontrato',
        'ver_todo_tramite',
    ];

    protected $casts = [
        'cdgo_lgin' => 'integer',
        'cdgo_prfle' => 'integer',
        'cdgo_crgo' => 'integer',
        'cdgo_usrios' => 'integer',
        'de_lgin' => 'string',
        'de_pass' => 'string',
        'ts_usua_modi' => 'datetime',
        'co_usua_modi' => 'integer',
        'in_esta' => 'string',
        'de_urls_frma' => 'string',
        'cdgo_sigex' => 'string',
        'lgin_sigex' => 'string',
        'tpocontrato' => 'string',
        'ver_todo_tramite' => 'string',
    ];

    public function dtoNtrnos()
    {
        return $this->hasMany(DtoNtrnosGestrad::class, 'cdgo_lgin', 'cdgo_lgin');
    }

    public function pUsrio()
    {
        return $this->belongsTo(PUsrios::class, 'cdgo_usrios', 'cdgo_usrios');
    }
}
