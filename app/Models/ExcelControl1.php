<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelControl1 extends Model
{
    protected $table = 'visitas.excel_control1';
    protected $fillable = [
        'hora_salida'
    ];
    public $timestamps = false;
}
