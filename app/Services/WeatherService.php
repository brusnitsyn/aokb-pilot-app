<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function weatherByCoordinate(float $lat, float $lon) : array|null
    {
        $weatherUrl = config('openweathermap.url');
        $weatherKey = config('openweathermap.key');

        $params = [
            'lat' => $lat,
            'lon' => $lon,
            'lang' => 'ru',
            'units' => 'metric',
            'appid' => $weatherKey
        ];

        try {
            $response = Http::get($weatherUrl, $params);
        } catch (ConnectionException $connectionException) {
            \Log::error($connectionException->getMessage());
            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        $response = json_decode($response->body(), true);

        $weather = [
            'weather' => $response['weather'][0]['description'], // Погода
            'temp' => $response['main']['temp'], // Температура
            'humidity' => $response['main']['humidity'], // Влажность
            'pressure' => $response['main']['pressure'], // Атмосферное давление
            'visibility' => $response['visibility'], // Видимость
            'wind_speed' => $response['wind']['speed'], // Скорость ветра
            'wind_gust' => array_key_exists('gust', $response['wind']) ? $response['wind']['gust'] : null, // Порыв ветра
            'wind_deg' => $response['wind']['deg'], // Направление ветра
            'clouds' => $response['clouds']['all'], // Облачность
        ];

        return $weather;
    }

    public function weatherCachedByCoordinate(float $lat, float $lon) : array|null
    {
        $cacheKey = "weather_{$lat}_$lon";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($lat, $lon) {
            return $this->weatherByCoordinate($lat, $lon);
        });
    }
}
