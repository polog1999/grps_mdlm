<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrAcceso extends Model
{
    protected $connection = 'pgsql_qr';

    protected $table = 'qr.qracceso';
    protected $primaryKey = 'qra_id';
    const CREATED_AT = 'qra_fecha';
    const UPDATED_AT = 'qra_filafecha';
    protected $fillable = [
        'cqr_id',
        'pai_id',
        'nve_id',
        'equ_id',
        'pla_id',
        'sio_id',
        'qra_ip',
        'qra_realip',
        'qra_macaddress',
        'qra_hostname',
        'qra_agente',
        'qra_filaoriginal',
        'qra_filaeliminada',
    ];

    protected $casts = [
        'qra_fecha' => 'datetime',
        'qra_filafecha' => 'datetime',
        'qra_filaoriginal' => 'boolean',
        'qra_filaeliminada' => 'boolean',
        'qra_id' => 'integer',
        'cqr_id' => 'integer',
    ];

    /**
     * RELACIONES
     */

    public function codigoQr(): BelongsTo
    {
        return $this->belongsTo(CodigoQr::class, 'cqr_id', 'cqr_id');
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'pai_id', 'pai_id');
    }
}