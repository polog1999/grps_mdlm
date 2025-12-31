<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla syscat.fichaubicacion
 * 
 * Representa la información de ubicación catastral de los predios
 */
class FichaUbicacion extends Model
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
    protected $table = 'syscat.fichaubicacion';

    /**
     * Clave primaria de la tabla
     * 
     * @var string
     */
    protected $primaryKey = 'fiu_id';

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
        'urb_id',
        'via_id',
        'fiu_coduca',
        'fiu_secuca',
        'fiu_codvia',
        'fiu_numvia',
        'fiu_intdpto',
        'fiu_blockedif',
        'fiu_codtippue',
        'fiu_codubifre',
        'fiu_codurb',
        'fiu_manzana',
        'fiu_lote',
        'fiu_zonificacion',
        'fiu_areaeconomica',
        'fiu_viascodi',
        'fiu_zonacodi',
        'fiu_filaoriginal',
        'fiu_filaeliminada',
        'fiu_codpre',
        'fiu_modificado',
        'fiu_secubipre',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'fiu_id' => 'integer',
        'urb_id' => 'integer',
        'via_id' => 'integer',
        'fiu_coduca' => 'string',
        'fiu_secuca' => 'string',
        'fiu_codvia' => 'string',
        'fiu_numvia' => 'string',
        'fiu_intdpto' => 'string',
        'fiu_blockedif' => 'string',
        'fiu_codtippue' => 'string',
        'fiu_codubifre' => 'string',
        'fiu_codurb' => 'string',
        'fiu_manzana' => 'string',
        'fiu_lote' => 'string',
        'fiu_zonificacion' => 'string',
        'fiu_areaeconomica' => 'decimal:2',
        'fiu_viascodi' => 'string',
        'fiu_zonacodi' => 'string',
        'fiu_filaoriginal' => 'boolean',
        'fiu_filaeliminada' => 'boolean',
        'fiu_codpre' => 'string',
        'fiu_modificado' => 'boolean',
        'fiu_secubipre' => 'string',
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
        return $query->where('fiu_filaeliminada', false);
    }

    /**
     * Scope para filtrar solo registros originales
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOriginales($query)
    {
        return $query->where('fiu_filaoriginal', true);
    }

    /**
     * Scope para buscar por código catastral (CODUCA)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $coduca
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCoduca($query, string $coduca)
    {
        return $query->where('fiu_coduca', $coduca);
    }

    /**
     * Scope para buscar por código predial
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $codpre
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCodigoPredial($query, string $codpre)
    {
        return $query->where('fiu_codpre', $codpre);
    }

    /**
     * Relación con Via (una ficha pertenece a una vía)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function via()
    {
        return $this->belongsTo(Via::class, 'via_id', 'via_id');
    }

    /**
     * Relación con Urbanizacion (una ficha pertenece a una urbanización)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function urbanizacion()
    {
        return $this->belongsTo(Urbanizacion::class, 'urb_id', 'urb_id');
    }
}
