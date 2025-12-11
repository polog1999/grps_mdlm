<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoCodigoQR extends Model
{
    protected $connection = 'pgsql_qr';

    protected $table = 'qr.tipocodigoqr';

    // 2. Llave Primaria
    protected $primaryKey = 'tqr_id';

    // 3. Timestamps
    const CREATED_AT = 'tqr_fecha';
    const UPDATED_AT = 'tqr_filafecha';

    // 4. Asignación Masiva
    protected $fillable = [
        'obs_id',
        'tqr_item',
        'tqr_url',
        'tqr_descripcion',
        'tqr_idsolicitado',
        'tqr_keysolicitado',
        'tqr_bd',
        'tqr_esquema',
        'tqr_query',
        'tqr_keycampo',
        'tqr_activo',
        'tqr_filaoriginal',
        'tqr_filaeliminada',
        'usa_id',
        'tqr_rutaqr',
    ];

    // 5. Conversión de Tipos (Casts)
    protected $casts = [
        // Booleanos (PostgreSQL los guarda como t/f o 1/0)
        'tqr_idsolicitado' => 'boolean',
        'tqr_keysolicitado' => 'boolean',
        'tqr_activo' => 'boolean',
        'tqr_filaoriginal' => 'boolean',
        'tqr_filaeliminada' => 'boolean',

        // Fechas
        'tqr_fecha' => 'datetime',
        'tqr_filafecha' => 'datetime',

        // Enteros
        'tqr_id' => 'integer',
        'obs_id' => 'integer',
        'tqr_item' => 'integer',
        'usa_id' => 'integer',
    ];


    public function codigosQr(): HasMany
    {
        return $this->hasMany(CodigoQr::class, 'tqr_id', 'tqr_id');
    }


    public function paginas(): HasMany
    {
        return $this->hasMany(TipoQrPagina::class, 'tqr_id', 'tqr_id');
    }

}