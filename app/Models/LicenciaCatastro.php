<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para la tabla licencia.licenciacatastro
 * 
 * Representa la relación entre licencias y datos catastrales
 */
class LicenciaCatastro extends Model
{
    /**
     * Conexión a la base de datos PostgreSQL
     */
    protected $connection = 'pgsql_licencias';

    /**
     * Nombre de la tabla
     */
    protected $table = 'licencia.licenciacatastro';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'lca_id';

    /**
     * Tipo de clave primaria
     */
    protected $keyType = 'integer';

    /**
     * Indica si la clave primaria es auto-incremental
     */
    public $incrementing = true;

    /**
     * Indica si el modelo debe usar timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'lic_id',
        'lca_descripcion',
        'lca_urbanizacion',
        'lca_zonificacion',
        'lca_origen',
        'lca_filaoriginal',
        'lca_filaeliminada',
        'fiu_id_infocat',
        'fiu_id_syscat',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'lca_id' => 'integer',
        'lic_id' => 'integer',
        'lca_descripcion' => 'string',
        'lca_urbanizacion' => 'string',
        'lca_zonificacion' => 'string',
        'lca_origen' => 'string',
        'lca_filaoriginal' => 'boolean',
        'lca_filaeliminada' => 'boolean',
        'fiu_id_infocat' => 'integer',
        'fiu_id_syscat' => 'integer',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con la licencia
     */
    public function licencia(): BelongsTo
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id', 'lic_id');
    }

    /**
     * Relación con la ficha de ubicación de INFOCAT
     */
    public function fichaUbicacionInfocat(): BelongsTo
    {
        return $this->belongsTo(FichaUbicacionInfocat::class, 'fiu_id_infocat', 'fiu_id');
    }

    /**
     * Relación con la ficha de ubicación de SYSCAT
     */
    public function fichaUbicacionSyscat(): BelongsTo
    {
        return $this->belongsTo(FichaUbicacionSyscat::class, 'fiu_id_syscat', 'fiu_id');
    }

}
