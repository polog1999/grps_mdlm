<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitaHistorico extends Model
{
   protected $table = 'visitas.historico_visitas';
   protected $guarded = [];

   public function sede(){
      return $this->belongsTo(Sede::class,'sede_id','id_sede');
   }
   public function area1(){
      return $this->belongsTo(Area::class,'area_id','id_unidad_organica');
   }
   public function userIngreso(){
      return $this->belongsTo(User::class,'user_id_ingreso');
   }
   public function userSalida(){
      return $this->belongsTo(User::class,'user_id_salida');
   }
// public function trabajadores()
// {
//     return $this->hasManyThrough(
//         visit::class,   // El modelo final que quieres (los trabajadores)
//         Visita::class,       // El modelo intermedio (la tabla visitas física)
//         'id',                // Llave foránea en Visita que coincide con tu Vista (visita_id)
//         'dependencia_id',    // Llave foránea en PersonaUno que apunta al jefe
//         'id_original',       // Llave local en tu Vista historico_visitas
//         'persona_id'         // Llave local en Visita que apunta a la Persona Principal
//     );
// }
// public function trabajadores()
// {
//     return $this->hasMany(VisitaTrabajadorRuc::class,'visita_id','id_original');
// }
// public function persona(){
//    ruturn $this-
// }
}
