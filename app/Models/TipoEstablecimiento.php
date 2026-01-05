<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla licencia.tipoestablecimiento
 * 
 * Representa los tipos de establecimiento para licencias
 */
class TipoEstablecimiento extends Model
{
    /**
     * Conexión a la base de datos
     */
    protected $connection = 'pgsql_licencias';

    /**
     * Nombre de la tabla
     */
    protected $table = 'licencia.tipoestablecimiento';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'tes_id';

    /**
     * Indica si el modelo debe usar timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'tes_id',
        'tes_descripcion',
        'tes_filaoriginal',
        'tes_filaeliminada',
        'coduso',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'tes_id' => 'integer',
        'tes_descripcion' => 'string',
        'tes_filaoriginal' => 'boolean',
        'tes_filaeliminada' => 'boolean',
        'coduso' => 'string',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con el uso catastral (Oracle)
     * Nota: Esta es una relación cross-database
     */
    public function usoCatastral()
    {
        return $this->belongsTo(Macatuso::class, 'coduso', 'CODUSO');
    }

    /**
     * Relación con licencias
     * Un tipo de establecimiento puede tener muchas licencias
     */
    public function licencias()
    {
        return $this->hasMany(CertificadoLicenciaFuncionamiento::class, 'tes_id', 'tes_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para obtener solo registros no eliminados
     */
    public function scopeNoEliminados($query)
    {
        return $query->where('tes_filaeliminada', false);
    }

    /**
     * Scope para obtener solo registros originales
     */
    public function scopeOriginales($query)
    {
        return $query->where('tes_filaoriginal', true);
    }

    /**
     * Scope para buscar por descripción
     */
    public function scopeByDescripcion($query, string $descripcion)
    {
        return $query->where('tes_descripcion', 'LIKE', "%{$descripcion}%");
    }

    /**
     * Scope para filtrar por código de uso
     */
    public function scopeByCodigoUso($query, string $coduso)
    {
        return $query->where('coduso', $coduso);
    }

    /**
     * Scope para obtener solo con código de uso asignado
     */
    public function scopeConCodigoUso($query)
    {
        return $query->whereNotNull('coduso')->where('coduso', '!=', '');
    }

    /**
     * Scope para obtener solo sin código de uso asignado
     */
    public function scopeSinCodigoUso($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('coduso')->orWhere('coduso', '');
        });
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Accessor para obtener la descripción limpia
     */
    public function getDescripcionAttribute()
    {
        return trim($this->tes_descripcion);
    }

    /**
     * Accessor para obtener el código de uso limpio
     */
    public function getCodigoUsoLimpioAttribute()
    {
        return trim($this->coduso ?? '');
    }

    // ==========================================
    // MÉTODOS HELPER
    // ==========================================

    /**
     * Verifica si tiene código de uso asignado
     */
    public function tieneCodigoUso(): bool
    {
        return !empty(trim($this->coduso ?? ''));
    }

    /**
     * Verifica si está eliminado
     */
    public function estaEliminado(): bool
    {
        return (bool) $this->tes_filaeliminada;
    }

    /**
     * Verifica si es original
     */
    public function esOriginal(): bool
    {
        return (bool) $this->tes_filaoriginal;
    }

    /**
     * Obtiene el uso catastral asociado (desde Oracle)
     * Nota: Requiere conexión a Oracle activa
     */
    public function obtenerUsoCatastral()
    {
        if (!$this->tieneCodigoUso()) {
            return null;
        }

        return Macatuso::byCodigo($this->coduso)->first();
    }
}
