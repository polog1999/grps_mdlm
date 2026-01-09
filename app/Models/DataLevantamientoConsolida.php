<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla catastro.data_levantamiento_consolida
 * 
 * Representa los datos consolidados de levantamiento catastral
 */
class DataLevantamientoConsolida extends Model
{
    /**
     * Conexión a la base de datos PostgreSQL
     */
    protected $connection = 'pgsql_finereport';

    /**
     * Nombre de la tabla
     */
    protected $table = 'catastro.data_levantamiento_consolida';

    /**
     * Usamos 'sml' como clave primaria virtual (Sector-Manzana-Lote)
     * La tabla no tiene PK real, pero esto permite que Filament funcione correctamente
     */
    protected $primaryKey = 'sml';

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
        'feclevan',
        'sml',
        'mza_urb',
        'lot_urb',
        'img_edificacion',
        'npisos',
        'usopredom',
        'flg_otrousos',
        'det_ousos',
        'numacteco',
        'giro1',
        'img_licencia',
        'img_itse',
        'giro2',
        'img_lic_g2',
        'giro3',
        'tienelf1',
        'img_lic_g3',
        'giro4',
        'tienelf5',
        'img_lf_gir4',
        'giro5',
        'tienelf2',
        'tienelf3',
        'tienelf4',
        'img_lf_gir31',
        'img_lf_gir41',
        'img_lf_gir5',
        'ei_cam_vigil',
        'publicidad',
        'ei_estacionam',
        'reja',
        'ei_otros',
        'ei_dotros',
        'num_estacionam',
        'img_ei',
        'numacteco1',
        'ae_ambul_giro1',
        'ae_tipo_estructura_1',
        'otro_amb_01',
        'img_ae_amb_01',
        'ae_ambul_giro2',
        'ae_tipo_estructura_2',
        'img_ae_amb_02',
        'ae_ambul_giro3',
        'ae_tipo_estructura_3',
        'otro_amb_02',
        'otro_amb_03',
        'img_ae_amb_021',
        'observa',
        'autoriza_gir1',
        'certif_itse1',
        'cesto_basura',
        'estamb_01',
        'estamb02',
        'esp_otro',
        'estado_terreno',
        'hidrante',
        'otros_usos_espec',
        'publicidad_externa',
        'correo',
        'num_act_amb',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        // Campos de tipo character varying (string)
        'feclevan' => 'string',
        'sml' => 'string',
        'mza_urb' => 'string',
        'lot_urb' => 'string',
        'img_edificacion' => 'string',
        'npisos' => 'string',
        'usopredom' => 'string',
        'det_ousos' => 'string',
        'numacteco' => 'string',
        'giro1' => 'string',
        'img_licencia' => 'string',
        'img_itse' => 'string',
        'giro2' => 'string',
        'img_lic_g2' => 'string',
        'giro3' => 'string',
        'tienelf1' => 'string',
        'img_lic_g3' => 'string',
        'giro4' => 'string',
        'tienelf5' => 'string',
        'img_lf_gir4' => 'string',
        'giro5' => 'string',
        'tienelf2' => 'string',
        'tienelf3' => 'string',
        'tienelf4' => 'string',
        'img_lf_gir31' => 'string',
        'img_lf_gir41' => 'string',
        'img_lf_gir5' => 'string',
        'ei_cam_vigil' => 'string',
        'publicidad' => 'string',
        'ei_estacionam' => 'string',
        'reja' => 'string',
        'ei_otros' => 'string',
        'ei_dotros' => 'string',
        'num_estacionam' => 'string',
        'img_ei' => 'string',
        'numacteco1' => 'string',
        'ae_ambul_giro1' => 'string',
        'ae_tipo_estructura_1' => 'string',
        'otro_amb_01' => 'string',
        'img_ae_amb_01' => 'string',
        'ae_ambul_giro2' => 'string',
        'ae_tipo_estructura_2' => 'string',
        'img_ae_amb_02' => 'string',
        'ae_ambul_giro3' => 'string',
        'ae_tipo_estructura_3' => 'string',
        'otro_amb_02' => 'string',
        'otro_amb_03' => 'string',
        'img_ae_amb_021' => 'string',
        'observa' => 'string',
        'autoriza_gir1' => 'string',
        'certif_itse1' => 'string',
        'cesto_basura' => 'string',
        'estamb_01' => 'string',
        'estamb02' => 'string',
        'correo' => 'string',

        // Campos de tipo text (string)
        'flg_otrousos' => 'string',
        'esp_otro' => 'string',
        'estado_terreno' => 'string',
        'hidrante' => 'string',
        'otros_usos_espec' => 'string',
        'publicidad_externa' => 'string',
        'num_act_amb' => 'string',
    ];

    // ==========================================
    // RELACIONES PERSONALIZADAS
    // ==========================================

    /**
     * Obtiene las fichas de ubicación de INFOCAT relacionadas
     * Relación basada en: substring(fiu_codcat, 3, 6) = sml
     */
    public function fichasUbicacionInfocat()
    {
        return FichaUbicacionInfocat::whereRaw(
            "substring(fiu_codcat, 3, 6) = ?",
            [$this->sml]
        )->get();
    }

    /**
     * Obtiene las fichas de ubicación de SYSCAT relacionadas
     * Relación basada en: substring(fiu_coduca, 7, 6) = sml
     */
    public function fichasUbicacionSyscat()
    {
        return FichaUbicacionSyscat::whereRaw(
            "substring(fiu_coduca, 7, 6) = ?",
            [$this->sml]
        )->get();
    }

    /**
     * Obtiene la primera ficha de ubicación de INFOCAT relacionada
     */
    public function fichaUbicacionInfocat()
    {
        return FichaUbicacionInfocat::whereRaw(
            "substring(fiu_codcat, 3, 6) = ?",
            [$this->sml]
        )->first();
    }

    /**
     * Obtiene la primera ficha de ubicación de SYSCAT relacionada
     */
    public function fichaUbicacionSyscat()
    {
        return FichaUbicacionSyscat::whereRaw(
            "substring(fiu_coduca, 7, 6) = ?",
            [$this->sml]
        )->first();
    }

}
