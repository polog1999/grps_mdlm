<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    // 1. Indica que no tiene ID autoincremental
    // public $incrementing = false;
    protected $table = 'visitas.visitas';
    // 3. Si de plano nada es único, al menos evita que Laravel busque el ID
    // protected $keyType = 'date';
    // protected $primaryKey = 'fecha';
    protected $guarded = [];

public function personas()
{
    // Define la relación muchos a muchos indicando la tabla pivote
    return $this->belongsToMany(PersonaUno::class, 'visitas.visita_persona', 'visita_id', 'persona_id')
                ->withPivot('cargo')
                ->withTimestamps();
}
    public function persona()
    {
        return $this->belongsTo(PersonaUno::class);
    }

    public function trabajadorAutoriza()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id_autoriza');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function userCreo()
    {
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi()
    {
        return $this->belongsTo(User::class, 'user_id_modi');
    }
//     protected function handleRecordCreation(array $data): Model
// {
//     // Eliminamos manualmente cada campo que NO existe en la tabla "visitas"
//     // para que PostgreSQL no lance el error de "Undefined column"
//     unset(
//         $data['tipo_documento_id'],
//         $data['numero_documento'],
//         $data['pide_fallo'],
//         $data['nombres'],
//         $data['apellido_paterno'],
//         $data['apellido_materno'],
//         $data['foto_url'],
//         $data['cargo'],
//         $data['lista_trabajadores'] // <--- Esto es vital para que no intente insertar un array
//     );

//     // Ahora $data está limpio y solo tiene campos reales de tu tabla
//     return Visita::create($data);
// }
}
