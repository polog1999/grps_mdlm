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

        $token = 'sk_12033.5bubrAsJTdWkxxhkZACfP2FSaEE4nUDS';


        $response = Http::withOptions([
            'verify' => false, // Equivale a 'verify' => false de Guzzle
            'connect_timeout' => 5,
        ])
            ->withHeaders([
                'Referer' => 'https://api.decolecta.com/v1/sunat/ruc',
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ])
            ->withToken($token) // Configura automáticamente el Bearer token
            ->get("https://api.decolecta.com/v1/sunat/ruc?numero={$ruc}");

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
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InBvbG9nMTk5OUBnbWFpbC5jb20ifQ.6P29nBVsMtqIhnv2T3koQAnnocm2UzJLk4uvfouXHKA';


        $response = Http::withOptions([
            'verify' => false, // Equivale a 'verify' => false de Guzzle
            'connect_timeout' => 5,
        ])
            ->withHeaders([
                'Referer' => 'https://dniruc.apisperu.com/api/v1/ruc/',
                'User-Agent' => 'laravel/guzzle',
                'Accept' => 'application/json',
            ])
            ->withToken($token) // Configura automáticamente el Bearer token
            ->get("https://dniruc.apisperu.com/api/v1/ruc/{$ruc}");

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
