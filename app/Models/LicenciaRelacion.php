<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LicenciaRelacion extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia.licencialicencia';
    protected $primaryKey = 'lil_id';
    public $timestamps = true;

    protected $fillable = [
        'lic_id',
        'lic_id_dependencia',
        'esl_id',
        'lil_item',
        'lil_fecha',
        'lil_filaoriginal',
        'lil_filaeliminada',
        'old_lic_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'lil_id' => 'integer',
        'lic_id' => 'integer',
        'lic_id_dependencia' => 'integer',
        'esl_id' => 'integer',
        'lil_item' => 'integer',
        'lil_fecha' => 'datetime',
        'lil_filaoriginal' => 'boolean',
        'lil_filaeliminada' => 'boolean',
        'old_lic_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? $model->created_by;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? $model->updated_by;

            // Si el lic_id ha cambiado, guardar el valor anterior en old_lic_id
            if ($model->isDirty('lic_id')) {
                $model->old_lic_id = $model->getOriginal('lic_id');
            }
        });
    }

    public function licencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id', 'lic_id');
    }

    public function licenciaDependencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id_dependencia', 'lic_id');
    }

    public function licenciaEstado()
    {
        return $this->belongsTo(TipoEstadoLicencia::class, 'esl_id', 'esl_id');
    }

    public function transferir(int $nuevoLicId, string $motivo): void
    {
        $licIdAnterior = $this->lic_id;
        $this->update([
            'lic_id' => $nuevoLicId,
        ]);
        Log::info("Transferencia de licencia en SIL", [
            'event' => 'license.transferred',
            'relation_id' => $this->lil_id,
            'old_license_id' => $this->old_lic_id,
            'new_license_id' => $licIdAnterior,
            'dependency_id' => $this->lic_id_dependencia,
            'user_email' => auth()->user()->email,
            'audit_motive' => $motivo,
            'source' => 'filament_table_action'
        ]);
    }
}