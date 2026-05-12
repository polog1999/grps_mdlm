<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SoapClient;
use SoapFault;

class RucService
{
    public static function apiRuc(string $ruc)
    {

        $token = env('APIRUC_TOKEN');
        $url = env('APIRUC_URL');

        $response = Http::withOptions([
            'verify' => false, // Equivale a 'verify' => false de Guzzle
            'connect_timeout' => 5,
        ])
            ->withHeaders([
                'Referer' => $url,
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ])
            ->withToken($token) // Configura automáticamente el Bearer token
            ->get("{$url}?numero={$ruc}");

        if ($response->successful()) {
            $data = $response->json();
            // dd($data); // O usa return $data;
            $data['success'] = true;
            return $data;
        }
        return [
            'success' => false,
            'data' => null,
            'error' => 'No se pudo consultar el RUC'
        ];
    }
    public static function apisPeruRuc(string $ruc)
    {
        $token = env('APISPERURUC_TOKEN');
        $url = env('APISPERURUC_URL');

        $response = Http::withOptions([
            'verify' => false, // Equivale a 'verify' => false de Guzzle
            'connect_timeout' => 5,
        ])
            ->withHeaders([
                'Referer' => $url,
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ])
            ->withToken($token) // Configura automáticamente el Bearer token
            ->get("{$url}{$ruc}");

        if ($response->successful()) {
            $data = $response->json();

            // VALIDACIÓN CRÍTICA: 
            // Si el JSON trae 'success' y es falso, es que el RUC no existe en SUNAT
            if (isset($data['success']) && $data['success'] === false) {
                return [
                    'success' => false,
                    'error' => 'El RUC no fue encontrado en el padrón de SUNAT.'
                ];
            }

            // Si llegó aquí y no hubo error interno, el RUC es válido
            // Agregamos nuestra propia bandera de success para el frontend de Filament
            $data['success'] = true;
            return $data;
        }

        // Si el HTTP falló (401, 404, 500)
        return [
            'success' => false,
            'error' => 'Error de conexión con el servicio de consulta.'
        ];
    }
}
