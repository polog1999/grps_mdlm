<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla syscat.via
 * 
 * Representa la información de vías (calles, avenidas, etc.) del catastro
 */
class Via extends Model
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
    protected $table = 'syscat.via';

    /**
     * Clave primaria de la tabla
     * 
     * @var string
     */
    protected $primaryKey = 'via_id';

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
        'via_codvia',
        'via_descvia',
        'via_codtipvia',
        'via_viascodi',
        'via_flagarea',
        'via_flage',
        'via_filaoriginal',
        'via_filaeliminada',
        'vit_id',
        'via_descvia2',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'via_id' => 'integer',
        'via_codvia' => 'string',
        'via_descvia' => 'string',
        'via_codtipvia' => 'string',
        'via_viascodi' => 'string',
        'via_flagarea' => 'boolean',
        'via_flage' => 'boolean',
        'via_filaoriginal' => 'boolean',
        'via_filaeliminada' => 'boolean',
        'vit_id' => 'integer',
        'via_descvia2' => 'string',
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
        return $query->where('via_filaeliminada', false);
    }

    /**
     * Scope para filtrar solo registros originales
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOriginales($query)
    {
        return $query->where('via_filaoriginal', true);
    }

    /**
     * Scope para buscar por código de vía
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $codvia
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCodvia($query, string $codvia)
    {
        return $query->where('via_codvia', $codvia);
    }

    /**
     * Scope para buscar por descripción de vía
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $descripcion
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDescripcion($query, string $descripcion)
    {
        return $query->where('via_descvia', 'LIKE', "%{$descripcion}%")
            ->orWhere('via_descvia2', 'LIKE', "%{$descripcion}%");
    }

    /**
     * Scope para buscar por tipo de vía
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tipoVia
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTipoVia($query, string $tipoVia)
    {
        return $query->where('via_codtipvia', $tipoVia);
    }

    /**
     * Obtiene el nombre completo de la vía
     * 
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        $nombre = $this->via_descvia ?? '';

        if ($this->via_descvia2) {
            $nombre .= ' (' . $this->via_descvia2 . ')';
        }

        return $nombre;
    }

    /**
     * Relación con FichaUbicacion (una vía puede tener muchas fichas)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fichasUbicacion()
    {
        return $this->hasMany(FichaUbicacion::class, 'via_id', 'via_id');
    }

    /**
     * Relación con ViaTipo (una vía pertenece a un tipo de vía)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function viaTipo()
    {
        return $this->belongsTo(ViaTipo::class, 'vit_id', 'vit_id');
    }
}
