<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SoapClient;
use SoapFault;

class RucService{
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
    

}