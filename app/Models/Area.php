<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    protected $connection = 'mysql';
    protected $table = 'unidades_organicas';
    protected $primaryKey = 'id_unidad_organica';

    public function sede(){
        return $this->belongsTo(Sede::class, 'id_sede','id_sede');
    }
    public function area(){
        return $this->belongsTo(Area::class, 'dependencia_id','id_unidad_organica');
    }
   public function oficinas(){
        return $this->hasMany(Oficina::class, 'id_unidad_organica','id_unidad_organica');
    }

    
    // protected $primaryKey = 'id_area';
// use SoftDeletes; // 2. Usar el Trait

    // protected $dates = ['deleted_at'];
    // protected $fillable = [
    //     'sede_id',
    //     'nombre',
    //     'parent_id',
    //     'nombre_corto',
    //     'orden',
    //     'estado',
    //     'user_id_creo',
    //     'user_id_modi'
    // ];

    // Relación: Una Área pertenece a una Sede
    // public function sede(): BelongsTo
    // {
    //     return $this->belongsTo(Sede::class, 'sede_id');
    // }

    // // Relación: Una Área puede tener un área padre
    // public function parentArea(): BelongsTo
    // {
    //     return $this->belongsTo(Area::class, 'parent_id', 'id');
    // }
    
    
    
}