<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivo extends Model
{
    
    protected $table = 'visitas.motivos';
    protected $fillable = [
        'motivo',
        'user_id_creo',
        'user_id_modi'
    ];
    use SoftDeletes; // 2. Usar el Trait

    protected $dates = ['deleted_at'];
    
    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}
