<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Exception;

class ClimaController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Endpoint para obtener el clima actual.
     * 
     * @return JsonResponse
     */
    public function getClima(): JsonResponse
    {
        try {
            $data = $this->weatherService->getCurrentWeather();

            // Transformar la respuesta para el frontend
            $result = [
                'ciudad'             => $data['name'] ?? 'La Paz',
                'temperatura'        => round($data['main']['temp'] ?? 0) . '°C',
                'descripcion_clima'  => ucfirst($data['weather'][0]['description'] ?? 'Desconocido'),
                'humedad'            => ($data['main']['humidity'] ?? 0) . '%',
                'velocidad_viento'   => ($data['wind']['speed'] ?? 0) . ' m/s',
                'icono_clima'        => isset($data['weather'][0]['icon']) 
                                        ? "https://openweathermap.org/img/wn/{$data['weather'][0]['icon']}@2x.png" 
                                        : null,
            ];

            return response()->json([
                'status' => 'success',
                'data'   => $result
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint para obtener el pronóstico de una fecha específica.
     * 
     * @return JsonResponse
     */
    public function getPronostico(): JsonResponse
    {
        $fecha = request()->query('fecha'); // Formato YYYY-MM-DD

        if (!$fecha) {
            return response()->json(['status' => 'error', 'message' => 'Fecha requerida'], 400);
        }

        try {
            $data = $this->weatherService->getForecast();
            
            // Buscar la entrada más cercana a la fecha y mediodía (12:00:00)
            $forecastEntry = null;
            foreach ($data['list'] as $entry) {
                if (str_contains($entry['dt_txt'], $fecha)) {
                    $forecastEntry = $entry;
                    // Preferir el pronóstico de las 12:00 o 15:00 si existen
                    if (str_contains($entry['dt_txt'], '12:00:00') || str_contains($entry['dt_txt'], '15:00:00')) {
                        break;
                    }
                }
            }

            if (!$forecastEntry) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No hay pronóstico disponible para esta fecha todavía.'
                ], 200);
            }

            $result = [
                'temperatura'       => round($forecastEntry['main']['temp'] ?? 0) . '°C',
                'descripcion_clima' => ucfirst($forecastEntry['weather'][0]['description'] ?? 'Desconocido'),
                'icono_clima'       => "https://openweathermap.org/img/wn/{$forecastEntry['weather'][0]['icon']}@2x.png",
                'lluvia_probable'   => (str_contains(strtolower($forecastEntry['weather'][0]['main']), 'rain') || 
                                        str_contains(strtolower($forecastEntry['weather'][0]['description']), 'lluvia')),
            ];

            return response()->json([
                'status' => 'success',
                'data'   => $result
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
