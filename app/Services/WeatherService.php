<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WeatherService
{
    protected $apiKey;
    protected $city;
    protected $units;
    protected $lang;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHER_API_KEY');
        $this->city   = env('OPENWEATHER_CITY', 'La Paz,bo');
        $this->units  = env('OPENWEATHER_UNITS', 'metric');
        $this->lang   = env('OPENWEATHER_LANG', 'es');
    }

    /**
     * Obtiene el clima actual desde OpenWeatherMap.
     */
    public function getCurrentWeather()
    {
        try {
            $response = Http::get($this->baseUrl, [
                'q'     => $this->city,
                'appid' => $this->apiKey,
                'units' => $this->units,
                'lang'  => $this->lang,
            ]);

            if ($response->failed()) {
                Log::error('Error en la API de Clima: ' . $response->body());
                throw new Exception('No se pudo obtener la información del clima.');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Excepción al conectar con OpenWeatherMap: ' . $e->getMessage());
            throw new Exception('Error de conexión con el servicio de clima.');
        }
    }

    /**
     * Obtiene la predicción del clima (5 días cada 3 horas).
     */
    public function getForecast()
    {
        try {
            $url = 'https://api.openweathermap.org/data/2.5/forecast';
            $response = Http::get($url, [
                'q'     => $this->city,
                'appid' => $this->apiKey,
                'units' => $this->units,
                'lang'  => $this->lang,
            ]);

            if ($response->failed()) {
                Log::error('Error en la API de Pronóstico: ' . $response->body());
                throw new Exception('No se pudo obtener la predicción del clima.');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Excepción al conectar con Pronóstico: ' . $e->getMessage());
            throw new Exception('Error de conexión con el servicio de pronóstico.');
        }
    }
}
