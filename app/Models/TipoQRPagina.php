<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipoQRPagina extends Model
{
    protected $connection = 'pgsql_qr';

    protected $table = 'qr.tipoqrpagina';

    // 2. Llave Primaria
    protected $primaryKey = 'tqp_id';

    // 3. Timestamps
    const CREATED_AT = 'tqp_filafecha';
    const UPDATED_AT = null; // No veo columna de actualización, así que la desactivamos

    // 4. Asignación Masiva
    protected $fillable = [
        'pla_id',
        'pac_id',
        'tqr_id',
        'tqp_activo',
        'tqp_filafecha',
        'tqp_filaoriginal',
        'tqp_filaeliminada',
        'usa_id',
    ];

    // 5. Conversión de Tipos (Casts)
    protected $casts = [
        'tqp_activo' => 'boolean',
        'tqp_filaoriginal' => 'boolean',
        'tqp_filaeliminada' => 'boolean',
        'tqp_filafecha' => 'datetime',
        'tqp_id' => 'integer',
        'pla_id' => 'integer',
        'pac_id' => 'integer',
        'tqr_id' => 'integer',
        'usa_id' => 'integer',
    ];

    public function tipoCodigoQr(): BelongsTo
    {
        return $this->belongsTo(TipoCodigoQr::class, 'tqr_id', 'tqr_id');
    }


    public function plataforma(): BelongsTo
    {
        return $this->belongsTo(Plataforma::class, 'pla_id', 'pla_id');
    }

}