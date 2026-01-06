<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para auditoría de licencias.
 * Usa la misma tabla que CertificadoLicenciaFuncionamiento pero con enfoque en auditoría.
 */
class AuditoriaLicencia extends Model
{
    /**
     * Nombre de la tabla en la base de datos.
     */
    protected $table = 'licencia.licencia';

    /**
     * Clave primaria de la tabla.
     */
    protected $primaryKey = 'lic_id';

    /**
     * Indica si el modelo debe usar timestamps automáticos.
     */
    public $timestamps = false;

    /**
     * Atributos que son asignables en masa.
     */
    protected $fillable = [
        'lic_numlic',
        'lic_expnum',
        'lic_creado_por',
        'lic_creado_en',
        'lic_actualizado_por',
        'lic_actualizado_en',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'lic_creado_en' => 'datetime',
        'lic_actualizado_en' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó la licencia.
     */
    public function creador()
    {
        return $this->belongsTo(\App\Models\User::class, 'lic_creado_por', 'id');
    }

    /**
     * Relación con el usuario que actualizó la licencia.
     */
    public function actualizador()
    {
        return $this->belongsTo(\App\Models\User::class, 'lic_actualizado_por', 'id');
    }
}
