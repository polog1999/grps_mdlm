<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Modelo para la tabla licencia.licencia_levantamiento
 * Relación entre licencias y estados de levantamiento de datos
 */
class LicenciaLevantamiento extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia.licencia_levantamiento';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'lic_id',
        'id_estado_levantamiento',
        'created_by',
        'updated_by',
        'observaciones',
    ];

    protected $casts = [
        'lic_id' => 'integer',
        'id_estado_levantamiento' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'observaciones' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Pertenece a una licencia
     */
    public function licencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id', 'lic_id');
    }

    /**
     * Relación: Pertenece a un estado de levantamiento
     */
    public function estadoLevantamiento()
    {
        return $this->belongsTo(EstadoLevantamiento::class, 'id_estado_levantamiento');
    }

    /**
     * Relación: Usuario que creó el registro
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación: Usuario que actualizó el registro
     */
    public function actualizadoPor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ==========================================
    // MÉTODOS DE AUDITORÍA
    // ==========================================

    /**
     * Registra la auditoría de creación
     */
    public function registrarCreacion(): void
    {
        $this->created_by = Auth::id();
    }

    /**
     * Registra la auditoría de actualización
     */
    public function registrarActualizacion(): void
    {
        $this->updated_by = Auth::id();
    }

    /**
     * Boot del modelo para registrar automáticamente la auditoría
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
