<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla syscat.viatipo
 * 
 * Representa los tipos de vías (Avenida, Calle, Jirón, etc.)
 */
class ViaTipo extends Model
{
    /**
     * Conexión de base de datos a utilizar
     * 
     * @var string
     */
    protected $connection = 'pgsql_syscat';

    /**
     * Nombre de la tabla en la base de datos
     * 
     * @var string
     */
    protected $table = 'syscat.viatipo';

    /**
     * Clave primaria de la tabla
     * 
     * @var string
     */
    protected $primaryKey = 'vit_id';

    /**
     * Indica si el modelo debe usar timestamps (created_at, updated_at)
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos que son asignables en masa
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'vit_codtipvia',
        'vit_desctipvia',
        'vit_abretipvia',
        'vit_filaoriginal',
        'vit_filaeliminada',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'vit_id' => 'integer',
        'vit_codtipvia' => 'string',
        'vit_desctipvia' => 'string',
        'vit_abretipvia' => 'string',
        'vit_filaoriginal' => 'boolean',
        'vit_filaeliminada' => 'boolean',
    ];

    /**
     * Atributos que deben ser ocultados en arrays
     * 
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Scope para filtrar solo registros no eliminados
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoEliminados($query)
    {
        return $query->where('vit_filaeliminada', false);
    }

    /**
     * Scope para filtrar solo registros originales
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOriginales($query)
    {
        return $query->where('vit_filaoriginal', true);
    }

    /**
     * Scope para buscar por código de tipo de vía
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $codigo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCodigo($query, string $codigo)
    {
        return $query->where('vit_codtipvia', $codigo);
    }

    /**
     * Scope para buscar por descripción
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $descripcion
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDescripcion($query, string $descripcion)
    {
        return $query->where('vit_desctipvia', 'LIKE', "%{$descripcion}%");
    }

    /**
     * Scope para buscar por abreviatura
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $abreviatura
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAbreviatura($query, string $abreviatura)
    {
        return $query->where('vit_abretipvia', $abreviatura);
    }

    /**
     * Obtiene el nombre completo con abreviatura
     * 
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        $nombre = $this->vit_desctipvia ?? '';

        if ($this->vit_abretipvia) {
            $nombre .= ' (' . $this->vit_abretipvia . ')';
        }

        return $nombre;
    }

    /**
     * Relación con Via (un tipo de vía puede tener muchas vías)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vias()
    {
        return $this->hasMany(Via::class, 'vit_id', 'vit_id');
    }
}
