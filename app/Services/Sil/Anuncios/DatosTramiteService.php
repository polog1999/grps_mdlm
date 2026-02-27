<?php

namespace App\Services\Sil\Anuncios;

use App\Models\DtoNtrnosGestrad;
use App\Models\PDtoTrmtes;
use Illuminate\Support\Facades\Log;


class DatosTramiteService
{
    public function getDatosTramite(string $nAnuncio, string $nExpediente)
    {
        if (blank($nAnuncio)) {
            Log::warning('getDatosTramite: El número de anuncio proporcionado está vacío.');
            return null;
        }

        try {
            $dtoNtrno = DtoNtrnosGestrad::select([
                'cdgo_dtos_ntrnos',
                'cdgo_area',
                'cdgo_lgin',
                'cdgo_anio',
                'nu_tram_todo',
                'nu_tram',
                'cdgo_dto_trmte',
                'de_obser',
                'fe_ingrso',
                'nu_tram_cmplto',
            ])
                ->with([
                    'aAnio' => function ($query) {
                        $query->select('cdgo_anio', 'de_anio', 'no_anio_fscal');
                    },
                    'pLgin' => function ($query) {
                        $query->select('cdgo_lgin', 'de_lgin', 'cdgo_usrios')
                            ->with([
                                'pUsrio' => function ($query) {
                                    $query->select('cdgo_usrios', 'no_crto', 'nu_docu');
                                }
                            ]);
                    },
                    /*
                    'rTrmtesNtrnos' => function ($query) {
                        $query->select('cdgo_trmtes_ntrnos', 'cdgo_dtos_ntrnos', 'cdgo_crgo_en', 'de_obser_ntrnos')
                            ->with([

                                'rCrgosLgin' => function ($query) {
                                    $query->select('cdgo_crgo_lgin', 'cdgo_lgin', 'cdgo_crgo', 'in_crgo_prmro')
                                        ->where('in_crgo_prmro', 1)
                                        ->with([
                                            'pLgin' => function ($query) {
                                                $query->select('cdgo_lgin', 'de_lgin', 'cdgo_usrios')
                                                    ->with([
                                                        'pUsrio' => function ($query) {
                                                            $query->select('cdgo_usrios', 'no_crto', 'nu_docu');
                                                        }
                                                    ]);
                                            },
                                            'pCrgo' => function ($query) {
                                                $query->select('cdgo_crgo', 'de_crgo');
                                            }
                                        ]);
                                }
                            ]);
                    },*/
                    'aSmllaIntrnos' => function ($query) {
                        $query->select('cdgo_dtos_ntrnos', 'ts_usua_modi');
                    }
                ])
                ->where('nu_tram_todo', $nAnuncio)
                ->where('cdgo_area', 29)
                ->where('cdgo_tpo_trmte', 8)
                ->first();

            if (!$dtoNtrno) {
                Log::warning("getDatosTramite: No se encontró el trámite para nu_tram_todo: {$nAnuncio} con los filtros de área 29 y tipo 8");
                return null;
            }

            // Buscamos los datos del trámite base por el número de expediente
            $dtoNtrno->setRelation('pDtoTrmte', PDtoTrmtes::select('cdgo_dto_trmte', 'nu_expe_todo', 'fe_ingr_trmte', 'cdgo_area')
                ->where('nu_expe_todo', $nExpediente)
                ->with([
                    'pCrgos' => function ($query) {
                        $query->select('cdgo_crgo', 'cdgo_area', 'de_crgo', 'nu_orde')
                            ->where('nu_orde', 1)
                            ->with([
                                'rCrgosLgins' => function ($query) {
                                    $query->select('cdgo_crgo_lgin', 'cdgo_lgin', 'cdgo_crgo', 'ts_usua_modi', 'in_esta', 'in_crgo_prmro')
                                        ->where('in_crgo_prmro', 1)
                                        ->with([
                                            'pLgin' => function ($query) {
                                                $query->select('cdgo_lgin', 'de_lgin', 'cdgo_usrios')
                                                    ->with([
                                                        'pUsrio' => function ($query) {
                                                            $query->select('cdgo_usrios', 'no_crto', 'nu_docu');
                                                        }
                                                    ]);
                                            }
                                        ]);
                                }
                            ]);
                    }
                ])
                ->first());

            return $dtoNtrno;

        } catch (\Exception $e) {
            Log::error('Error al obtener datos del tramite: ' . $e->getMessage(), [
                'nAnuncio' => $nAnuncio,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    //get first 100 filas de DtoNtrnosGestrad without cdgo area or cdgo tip tramite
    public function getFirst100rows()
    {
        try {
            $dtoNtrno = DtoNtrnosGestrad::limit(100)->get();

            if ($dtoNtrno->isEmpty()) {
                Log::warning("getFirst100rows: No se encontraron trámites.");
                return collect();
            }

            return $dtoNtrno;

        } catch (\Exception $e) {
            Log::error('Error al obtener datos del tramite: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return null;
        }
    }
}