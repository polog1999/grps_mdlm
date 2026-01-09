<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla infocat.fichaubicacion
 * 
 * Representa la ubicación de fichas catastrales en el sistema INFOCAT
 */
class FichaUbicacionInfocat extends Model
{
    /**
     * Conexión a la base de datos PostgreSQL
     */
    protected $connection = 'pgsql_licencias';

    /**
     * Nombre de la tabla
     */
    protected $table = 'infocat.fichaubicacion';

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
        'via_id',
        'urb_id',
        'fiu_codcat',
        'fiu_urbcod',
        'fiu_viacod',
        'fiu_manzana',
        'fiu_lote',
        'fiu_dep',
        'fiu_supmza',
        'fiu_areadeclarada',
        'fiu_areaverificada',
        'fiu_filaoriginal',
        'fiu_filaeliminada',
        'fiu_ubinum',
        'fiu_zonificacion',
        'fiu_codpre',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'fiu_id' => 'integer',
        'via_id' => 'integer',
        'urb_id' => 'integer',
        'fiu_codcat' => 'string',
        'fiu_urbcod' => 'string',
        'fiu_viacod' => 'string',
        'fiu_manzana' => 'string',
        'fiu_lote' => 'string',
        'fiu_dep' => 'string',
        'fiu_supmza' => 'string',
        'fiu_areadeclarada' => 'decimal:2',
        'fiu_areaverificada' => 'decimal:2',
        'fiu_filaoriginal' => 'boolean',
        'fiu_filaeliminada' => 'boolean',
        'fiu_ubinum' => 'string',
        'fiu_zonificacion' => 'string',
        'fiu_codpre' => 'string',
    ];

    // ==========================================
    // RELACIONES PERSONALIZADAS
    // ==========================================

    /**
     * Obtiene los registros de DataLevantamientoConsolida relacionados
     * Relación basada en: substring(fiu_codcat, 3, 6) = sml
     */
    public function dataLevantamientos()
    {
        if (empty($this->fiu_codcat) || strlen($this->fiu_codcat) < 8) {
            return collect([]);
        }

        $sml = substr($this->fiu_codcat, 2, 6); // PHP usa índice 0, substring SQL usa índice 1

        return DataLevantamientoConsolida::where('sml', $sml)->get();
    }

    /**
     * Obtiene el primer registro de DataLevantamientoConsolida relacionado
     */
    public function dataLevantamiento()
    {
        if (empty($this->fiu_codcat) || strlen($this->fiu_codcat) < 8) {
            return null;
        }

        $sml = substr($this->fiu_codcat, 2, 6);

        return DataLevantamientoConsolida::where('sml', $sml)->first();
    }
}
