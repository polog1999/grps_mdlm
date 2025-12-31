<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla syscat.urbanizacion
 * 
 * Representa las urbanizaciones del catastro
 */
class Urbanizacion extends Model
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
    protected $table = 'syscat.urbanizacion';

    /**
     * Clave primaria de la tabla
     * 
     * @var string
     */
    protected $primaryKey = 'urb_id';

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
        'urb_codurb',
        'urb_descurb',
        'urb_codtipurb',
        'urb_condurb',
        'urb_zonacodi',
        'urb_flagarea',
        'urb_filaoriginal',
        'urb_filaeliminada',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'urb_id' => 'integer',
        'urb_codurb' => 'string',
        'urb_descurb' => 'string',
        'urb_codtipurb' => 'string',
        'urb_condurb' => 'string',
        'urb_zonacodi' => 'string',
        'urb_flagarea' => 'boolean',
        'urb_filaoriginal' => 'boolean',
        'urb_filaeliminada' => 'boolean',
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
        return $query->where('urb_filaeliminada', false);
    }

    /**
     * Scope para filtrar solo registros originales
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOriginales($query)
    {
        return $query->where('urb_filaoriginal', true);
    }

    /**
     * Scope para buscar por código de urbanización
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $codigo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCodigo($query, string $codigo)
    {
        return $query->where('urb_codurb', $codigo);
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
        return $query->where('urb_descurb', 'LIKE', "%{$descripcion}%");
    }

    /**
     * Scope para buscar por tipo de urbanización
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tipoUrb
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTipoUrbanizacion($query, string $tipoUrb)
    {
        return $query->where('urb_codtipurb', $tipoUrb);
    }

    /**
     * Relación con FichaUbicacion (una urbanización puede tener muchas fichas)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fichasUbicacion()
    {
        return $this->hasMany(FichaUbicacion::class, 'urb_id', 'urb_id');
    }
}
