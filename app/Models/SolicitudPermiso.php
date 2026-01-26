<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudPermiso extends Model
{
    protected $table = 'solicitudes_permisos';

    protected $casts = [
        'estado' => \App\Enums\SolicitudPermisoEstado::class,
        'fecha_aprobacion' => 'datetime',
    ];

    protected $fillable = [
        'module_id',
        'record_id',
        'user_id',
        'tipo_accion',
        'estado',
        'admin_id',
        'fecha_aprobacion',
        'observacion',
    ];

    /**
     * Relación con el módulo.
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /**
     * El usuario que solicita el permiso.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * El administrador que aprobó/rechazó.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope para filtrar solo solicitudes pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }

    /**
     * Scope para filtrar solo solicitudes aprobadas.
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'APROBADO');
    }

    /**
     * Relación con CertificadoLicenciaFuncionamiento (solo válido si module_id = 2).
     */
    public function licencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'record_id', 'lic_id');
    }

}
