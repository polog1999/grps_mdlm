<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla syscat.fichaubicacion
 * 
 * Representa la ubicación de fichas catastrales en el sistema SYSCAT
 */
class FichaUbicacionSyscat extends Model
{
    /**
     * Conexión a la base de datos PostgreSQL
     */
    protected $connection = 'pgsql_licencias';

    /**
     * Nombre de la tabla
     */
    protected $table = 'syscat.fichaubicacion';

    /**
     * Clave primaria
     */
    protected $primaryKey = 'fiu_id';

    /**
     * Tipo de clave primaria
     */
    protected $keyType = 'integer';

    /**
     * Indica si la clave primaria es auto-incremental
     */
    public $incrementing = true;

    /**
     * Indica si el modelo debe usar timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
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

    // ==========================================
    // RELACIONES PERSONALIZADAS
    // ==========================================

    /**
     * Obtiene los registros de DataLevantamientoConsolida relacionados
     * Relación basada en: substring(fiu_coduca, 7, 6) = sml
     */
    public function dataLevantamientos()
    {
        if (empty($this->fiu_coduca) || strlen($this->fiu_coduca) < 12) {
            return collect([]);
        }

        $sml = substr($this->fiu_coduca, 6, 6); // PHP usa índice 0, substring SQL usa índice 1

        return DataLevantamientoConsolida::where('sml', $sml)->get();
    }

    /**
     * Obtiene el primer registro de DataLevantamientoConsolida relacionado
     */
    public function dataLevantamiento()
    {
        if (empty($this->fiu_coduca) || strlen($this->fiu_coduca) < 12) {
            return null;
        }

        $sml = substr($this->fiu_coduca, 6, 6);

        return DataLevantamientoConsolida::where('sml', $sml)->first();
    }
}
