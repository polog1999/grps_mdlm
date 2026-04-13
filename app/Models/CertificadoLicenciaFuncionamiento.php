<?php

namespace App\Models;
use App\Models\TipoLicencia;
use App\Models\TipoEstadoLicencia;
use App\Models\Persona;
use App\Models\User;
use App\Models\LicenciaLevantamiento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CertificadoLicenciaFuncionamiento extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia.licencia';
    protected $primaryKey = 'lic_id';
    public $timestamps = false;
    protected $fillable = [
        'lic_id',
        'tli_id',
        'tes_id',
        'per_idrazonsocial',
        'per_idsolicitante',
        'lic_numlic',
        'lic_codigopredial',
        'lic_expnum',
        'lic_direccion',
        'lic_urbanizacion',
        'lic_area',
        'lic_mype',
        'lic_resnum',
        'lic_fecharesolucion',
        'lic_fechaemision',
        'lic_fechavencimiento',
        'lic_licobs',
        'lic_filaoriginal',
        'lic_filaeliminada',
        'lic_giro',
        'lic_liccodi',
        'esl_id',
        'lic_migracion',
        'lic_cerrado',
        'lic_filafecha',
        'lic_horainicio',
        'lic_horafin',
        'tir_id',
        'lic_fechanotificacion',
        'lic_fechalimite',
        'usa_id',
        'lic_razonsocial',
        'lic_obscer',
        'lic_nota',
        'lic_compatibilidad',
        'lic_rsgparrafo1',
        'lic_rsgparrafo2',
        'tli_id_ant',
        'nir_id',
        'lic_expfec',
        'lic_compatibilidadnumero',
        'lic_compatibilidadfecha',
        // Auditoría
        'lic_creado_por',
        'lic_creado_en',
        'lic_actualizado_por',
        'lic_actualizado_en',
    ];

    protected $casts = [
        'lic_fecharesolucion' => 'date',
        'lic_fechaemision' => 'date',
        'lic_fechavencimiento' => 'date',
        'lic_fechanotificacion' => 'date',
        'lic_fechalimite' => 'date',
        'lic_expfec' => 'date',
        'lic_compatibilidadfecha' => 'date',
        'lic_filafecha' => 'datetime',
        'lic_mype' => 'boolean',
        'lic_filaoriginal' => 'boolean',
        'lic_filaeliminada' => 'boolean',
        'lic_cerrado' => 'boolean',
        // Auditoría
        'lic_creado_por' => 'integer',
        'lic_creado_en' => 'datetime',
        'lic_actualizado_por' => 'integer',
        'lic_actualizado_en' => 'datetime',
    ];

    public function tipoLicencia()
    {
        return $this->belongsTo(TipoLicencia::class, 'tli_id');
    }


    public function tipoEstadoLicencia()
    {
        return $this->belongsTo(TipoEstadoLicencia::class, 'esl_id');
    }

    /**
     * Relación con los giros de la licencia
     */
    public function giros()
    {
        return $this->hasMany(LicenciaGiro::class, 'lic_id', 'lic_id');
    }

    public function personaSolicitante()
    {
        return $this->belongsTo(Persona::class, 'per_idsolicitante', 'per_id');
    }

    /**
     * Relación con la persona razón social
     */
    public function personaRazonSocial()
    {
        return $this->belongsTo(Persona::class, 'per_idrazonsocial', 'per_id');
    }

    public function nivelRiesgo()
    {
        return $this->belongsTo(NivelRiesgo::class, 'nir_id');
    }

    /**
     * Usuario que creó el registro
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'lic_creado_por');
    }

    /**
     * Usuario que actualizó el registro
     */
    public function actualizadoPor()
    {
        return $this->belongsTo(User::class, 'lic_actualizado_por');
    }

    public function licenciaLevantamiento()
    {
        return $this->hasMany(LicenciaLevantamiento::class, 'lic_id');
    }

    /**
     * Relación para obtener el registro más reciente de licencia_levantamiento
     */
    public function licenciaLevantamientoReciente()
    {
        return $this->hasOne(LicenciaLevantamiento::class, 'lic_id')
            ->latestOfMany('created_at');
    }

    public function licenciaCatastro()
    {
        return $this->belongsTo(LicenciaCatastro::class, 'lic_id', 'lic_id');
    }

    // ==========================================
    // MÉTODOS DE AUDITORÍA
    // ==========================================

    /**
     * Registra la auditoría de creación
     */
    public function registrarCreacion(): void
    {
        $this->lic_creado_por = Auth::id();
        $this->lic_creado_en = now();
    }

    /**
     * Registra la auditoría de actualización
     */
    public function registrarActualizacion(): void
    {
        $this->lic_actualizado_por = Auth::id();
        $this->lic_actualizado_en = now();
    }

    public function licenciaBaja()
    {
        return $this->hasOne(LicenciaBaja::class, 'lic_id', 'lic_id');
    }
}
