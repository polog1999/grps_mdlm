<?php

namespace App\Models;
use App\Models\TipoLicencia;
use App\Models\TipoEstadoLicencia;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Model;
class CertificadoLicenciaFuncionamiento extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia';
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
}
