<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Modelo para la tabla DS_INFOCAT.MVCATFIND (Oracle)
 * 
 * Representa las fichas catastrales de predios
 */
class Mvcatfind extends Model
{
    /**
     * Conexión a la base de datos Oracle
     */
    protected $connection = 'oracle';

    /**
     * Nombre de la tabla
     */
    protected $table = 'DS_INFOCAT.MVCATFIND';

    /**
     * Clave primaria compuesta
     */
    protected $primaryKey = 'CODUCA';

    /**
     * Tipo de clave primaria
     */
    protected $keyType = 'string';

    /**
     * Indica si la clave primaria es auto-incremental
     */
    public $incrementing = false;

    /**
     * Indica si el modelo debe usar timestamps
     */
    public $timestamps = false;

    /**
     * Atributos asignables en masa
     */
    protected $fillable = [
        'CODUCA',
        'SECUCA',
        'CODPREDIO',
        'CODCLASIF',
        'CODESTCONST',
        'CODURB',
        'MZ',
        'LOTE',
        'SUPMZ',
        'SUBLOTE',
        'DESCEDIF',
        'OBSERVACION',
        'ESTRUCTU',
        'ZONIFICAC',
        'AREADECL',
        'AREAVERI',
        'LINFRENTE',
        'LINFONDO',
        'LINDERECHA',
        'LINIZQUIERDA',
        'SUMLUZ',
        'SUMAGUA',
        'CONEXGAS',
        'DESAGUE',
        'CODUSO',
        'BCODPROPIEDAD',
        'TOMOREGPU',
        'PARTELECREGPU',
        'FOLIOREGPU',
        'DOCUMENTOREGPU',
        'AREAINVJAR',
        'CODJARAIS',
        'USUARCREA',
        'FECHCREA',
        'HORACREA',
        'PCCREA',
        'ASIENTOREGPU',
        'FICHAREGPU',
        'PORAVAESCONS',
        'CONDLOTE',
        'ESTFICHA',
        'CODPROP',
        'CODENCU',
        'CODDIGI',
        'CODSCAMPO',
        'CODSCALIDAD',
        'CODCALIDAD',
        'CODDECLA',
        'FECHLEVANT',
        'FECHCALIDAD',
        'FECHDIG',
        'FECHRECEP',
        'FIRPROP',
        'FIRENCU',
        'FIRDIGI',
        'FIRSCAMPO',
        'FIRSCALIDAD',
        'FIRCALIDAD',
        'FIRDECLAR',
        'AREAINVADIDA',
        'USUARMOD',
        'FECHMOD',
        'HORAMOD',
        'PCMOD',
        'CODPREDACU',
        'NUMCEDULA',
        'NUMREQUER',
        'CODSDIGI',
        'FIRSDIGI',
        'FECHSDIG',
        'PORBCHIS',
        'FLAGACTIVA',
        'FLAGLLENA',
        'FECHDESDE',
        'FECHHASTA',
        'FLAGINSPEC',
        'BCCODIGO',
        'FLAGVALIDA',
        'CODZONIFICA',
        'CODFUENTE',
        'CODFTEINF',
        'USUARGABIN',
        'FECHGABIN',
        'HORAGABIN',
        'PCGABIN',
        'CUC',
        'HOJA_CAT',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        // Identificadores
        'CODUCA' => 'string',
        'SECUCA' => 'string',
        'CODPREDIO' => 'string',
        'CODCLASIF' => 'string',
        'CODESTCONST' => 'string',
        'CODURB' => 'string',
        'MZ' => 'string',
        'LOTE' => 'string',
        'SUPMZ' => 'string',
        'SUBLOTE' => 'string',
        'DESCEDIF' => 'string',
        'OBSERVACION' => 'string',
        'ESTRUCTU' => 'string',
        'ZONIFICAC' => 'string',

        // Áreas y medidas (números)
        'AREADECL' => 'decimal:2',
        'AREAVERI' => 'decimal:2',
        'LINFRENTE' => 'decimal:2',
        'LINFONDO' => 'decimal:2',
        'LINDERECHA' => 'decimal:2',
        'LINIZQUIERDA' => 'decimal:2',
        'AREAINVJAR' => 'decimal:2',
        'AREAINVADIDA' => 'decimal:2',
        'BCODPROPIEDAD' => 'integer',
        'PORAVAESCONS' => 'decimal:2',
        'PORBCHIS' => 'decimal:2',

        // Servicios
        'SUMLUZ' => 'string',
        'SUMAGUA' => 'string',
        'CONEXGAS' => 'string',
        'DESAGUE' => 'string',

        // Relación con uso catastral
        'CODUSO' => 'string',

        // Registro público
        'TOMOREGPU' => 'string',
        'PARTELECREGPU' => 'string',
        'FOLIOREGPU' => 'string',
        'DOCUMENTOREGPU' => 'string',
        'ASIENTOREGPU' => 'string',
        'FICHAREGPU' => 'string',

        // Códigos varios
        'CODJARAIS' => 'string',
        'CONDLOTE' => 'string',
        'ESTFICHA' => 'string',
        'CODPROP' => 'string',
        'CODENCU' => 'string',
        'CODDIGI' => 'string',
        'CODSCAMPO' => 'string',
        'CODSCALIDAD' => 'string',
        'CODCALIDAD' => 'string',
        'CODDECLA' => 'string',
        'CODPREDACU' => 'string',
        'CODSDIGI' => 'string',
        'BCCODIGO' => 'string',
        'CODZONIFICA' => 'string',
        'CODFUENTE' => 'string',
        'CODFTEINF' => 'string',
        'CUC' => 'string',
        'HOJA_CAT' => 'string',

        // Firmas
        'FIRPROP' => 'string',
        'FIRENCU' => 'string',
        'FIRDIGI' => 'string',
        'FIRSCAMPO' => 'string',
        'FIRSCALIDAD' => 'string',
        'FIRCALIDAD' => 'string',
        'FIRDECLAR' => 'string',
        'FIRSDIGI' => 'string',

        // Fechas
        'FECHCREA' => 'date',
        'FECHMOD' => 'date',
        'FECHLEVANT' => 'date',
        'FECHCALIDAD' => 'date',
        'FECHDIG' => 'date',
        'FECHRECEP' => 'date',
        'FECHSDIG' => 'date',
        'FECHDESDE' => 'date',
        'FECHHASTA' => 'date',
        'FECHGABIN' => 'date',

        // Horas
        'HORACREA' => 'string',
        'HORAMOD' => 'string',
        'HORAGABIN' => 'string',

        // PCs y usuarios
        'PCCREA' => 'string',
        'PCMOD' => 'string',
        'PCGABIN' => 'string',
        'USUARCREA' => 'string',
        'USUARMOD' => 'string',
        'USUARGABIN' => 'string',

        // Documentos
        'NUMCEDULA' => 'string',
        'NUMREQUER' => 'string',

        // Flags
        'FLAGACTIVA' => 'string',
        'FLAGLLENA' => 'string',
        'FLAGINSPEC' => 'string',
        'FLAGVALIDA' => 'string',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con el uso catastral
     */
    public function usoCatastral()
    {
        return $this->belongsTo(Macatuso::class, 'CODUSO', 'CODUSO');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para buscar por código UCA
     */
    public function scopeByCoduca($query, string $coduca)
    {
        return $query->where('CODUCA', $coduca);
    }

    /**
     * Scope para buscar por código de predio
     */
    public function scopeByCodigoPredio($query, string $codpredio)
    {
        return $query->where('CODPREDIO', $codpredio);
    }

    /**
     * Scope para buscar por urbanización
     */
    public function scopeByUrbanizacion($query, string $codurb)
    {
        return $query->where('CODURB', $codurb);
    }

    /**
     * Scope para buscar por manzana y lote
     */
    public function scopeByManzanaLote($query, string $mz, string $lote)
    {
        return $query->where('MZ', $mz)->where('LOTE', $lote);
    }

    /**
     * Scope para buscar por zonificación
     */
    public function scopeByZonificacion($query, string $zonificacion)
    {
        return $query->where('ZONIFICAC', 'LIKE', "%{$zonificacion}%");
    }

    /**
     * Scope para buscar por código de uso
     */
    public function scopeByUso($query, string $coduso)
    {
        return $query->where('CODUSO', $coduso);
    }

    /**
     * Scope para fichas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('FLAGACTIVA', 'S');
    }

    /**
     * Scope para fichas válidas
     */
    public function scopeValidas($query)
    {
        return $query->where('FLAGVALIDA', 'S');
    }

    /**
     * Scope para fichas con inspección
     */
    public function scopeConInspeccion($query)
    {
        return $query->where('FLAGINSPEC', 'S');
    }

    /**
     * Scope para predios con luz
     */
    public function scopeConLuz($query)
    {
        return $query->where('SUMLUZ', 'S');
    }

    /**
     * Scope para predios con agua
     */
    public function scopeConAgua($query)
    {
        return $query->where('SUMAGUA', 'S');
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Accessor para obtener el código UCA limpio
     */
    public function getCoducaLimpioAttribute()
    {
        return trim($this->CODUCA);
    }

    /**
     * Accessor para obtener la dirección formateada
     */
    public function getDireccionFormateadaAttribute()
    {
        $partes = [];

        if (trim($this->CODURB)) {
            $partes[] = 'URB. ' . trim($this->CODURB);
        }
        if (trim($this->MZ)) {
            $partes[] = 'MZ ' . trim($this->MZ);
        }
        if (trim($this->LOTE)) {
            $partes[] = 'LT ' . trim($this->LOTE);
        }
        if (trim($this->SUBLOTE)) {
            $partes[] = 'SUBLOTE ' . trim($this->SUBLOTE);
        }

        return implode(' ', $partes);
    }

    /**
     * Accessor para el área total
     */
    public function getAreaTotalAttribute()
    {
        return $this->AREAVERI ?? $this->AREADECL ?? 0;
    }

    // ==========================================
    // MÉTODOS HELPER
    // ==========================================

    /**
     * Verifica si la ficha está activa
     */
    public function estaActiva(): bool
    {
        return trim($this->FLAGACTIVA) === 'S';
    }

    /**
     * Verifica si la ficha es válida
     */
    public function esValida(): bool
    {
        return trim($this->FLAGVALIDA) === 'S';
    }

    /**
     * Verifica si tiene inspección
     */
    public function tieneInspeccion(): bool
    {
        return trim($this->FLAGINSPEC) === 'S';
    }

    /**
     * Verifica si tiene todos los servicios
     */
    public function tieneTodosServicios(): bool
    {
        return trim($this->SUMLUZ) === 'S'
            && trim($this->SUMAGUA) === 'S'
            && trim($this->DESAGUE) === 'S';
    }

    /**
     * Obtiene el uso catastral asociado
     */
    public function obtenerUsoCatastral()
    {
        return $this->usoCatastral;
    }
}
