<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoQR extends Model
{
    protected $connection = 'pgsql_qr';
    protected $table = 'qr.codigoqr';
    protected $primaryKey = 'cqr_id';

    const CREATED_AT = 'cqr_fecha';
    const UPDATED_AT = 'cqr_filafecha';

    protected $fillable = [
        'tqr_id',
        'cqr_descripcion',
        'cqr_observacion',
        'cqr_idasociado',
        'cqr_keyasociado',
        'cqr_qr',
        'cqr_filaoriginal',
        'cqr_filaeliminada',
        'usa_id',
    ];

    protected $casts = [
        'cqr_fecha' => 'datetime',
        'cqr_filafecha' => 'datetime',
        'cqr_filaoriginal' => 'boolean',
        'cqr_filaeliminada' => 'boolean',
        'cqr_id' => 'integer',
        'tqr_id' => 'integer',
        'cqr_idasociado' => 'integer',
        'usa_id' => 'integer',
    ];

    public function tipoCodigoQR(): BelongsTo
    {
        return $this->belongsTo(TipoCodigoQR::class, 'tqr_id', 'tqr_id');
    }
}