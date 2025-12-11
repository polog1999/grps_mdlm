<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Plataforma extends Model
{
    protected $connection = 'pgsql_public';
    protected $table = 'public.plataforma';

    // 2. Llave Primaria
    protected $primaryKey = 'pla_id';

    // 3. Timestamps
    // Solo tienes fecha de creación (pla_filafecha)
    const CREATED_AT = 'pla_filafecha';
    const UPDATED_AT = null;

    // 4. Asignación Masiva
    protected $fillable = [
        'pla_descripcion',
        'pla_sigla',        // Campo extra que no tenían los otros modelos
        'pla_activo',
        'pla_filafecha',
        'pla_filaoriginal',
        'pla_filaeliminada',
        'usa_id',
    ];

    // 5. Conversión de Tipos (Casts)
    protected $casts = [
        'pla_activo' => 'boolean',
        'pla_filaoriginal' => 'boolean',
        'pla_filaeliminada' => 'boolean',
        'pla_filafecha' => 'datetime',
        'pla_id' => 'integer',
        'usa_id' => 'integer',
    ];

    public function accesos(): HasMany
    {
        return $this->hasMany(QrAcceso::class, 'pla_id', 'pla_id');
    }

    public function tiposQrPagina(): HasMany
    {
        return $this->hasMany(TipoQrPagina::class, 'pla_id', 'pla_id');
    }
}