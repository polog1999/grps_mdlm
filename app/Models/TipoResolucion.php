<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla licencia.tiporesolucion
 * 
 * Representa los tipos de resolución disponibles para las licencias
 */
class TipoResolucion extends Model
{
    /**
     * Conexión a la base de datos
     */
    protected $connection = 'pgsql_licencias';

    /**
     * Nombre de la tabla
     */
    protected $table = 'licencia.tiporesolucion';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'tir_id';

    /**
     * Indica si el modelo debe usar timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'tir_id',
        'tir_descripcion',
        'tir_filafecha',
        'tir_filaoriginal',
        'tir_filaeliminada',
        'tir_activo',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'tir_id' => 'integer',
        'tir_descripcion' => 'string',
        'tir_filafecha' => 'datetime',
        'tir_filaoriginal' => 'boolean',
        'tir_filaeliminada' => 'boolean',
        'tir_activo' => 'boolean',
    ];

    /**
     * Scope para obtener solo registros no eliminados
     */
    public function scopeNoEliminados($query)
    {
        return $query->where('tir_filaeliminada', false);
    }

    /**
     * Scope para obtener solo registros activos
     */
    public function scopeActivos($query)
    {
        return $query->where('tir_activo', true);
    }

    /**
     * Scope para obtener solo registros originales
     */
    public function scopeOriginales($query)
    {
        return $query->where('tir_filaoriginal', true);
    }

    /**
     * Scope para buscar por descripción
     */
    public function scopeByDescripcion($query, string $descripcion)
    {
        return $query->where('tir_descripcion', 'LIKE', "%{$descripcion}%");
    }

    /**
     * Relación con licencias
     * Una tipo de resolución puede tener muchas licencias
     */
    public function licencias()
    {
        return $this->hasMany(CertificadoLicenciaFuncionamiento::class, 'tir_id', 'tir_id');
    }
}
