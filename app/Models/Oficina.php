<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $connection = 'mysql';
    protected $table = 'uo_oficinas';
    protected $primaryKey = 'id_oficina';


    public function area(){
      return $this->belongsTo(Area::class,'id_unidad_organica','id_unidad_organica');
   }
    // public function area()
    // {
    //     return $this->belongsTo(Oficina::class, 'id_unidad_organica','id_unidad_organica');
    // }
}
