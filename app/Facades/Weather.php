<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|null weatherByCoordinate(float $lat, float $lon)
 * @method static array|null weatherCachedByCoordinate(float $lat, float $lon)
 *
 * @see \App\Services\WeatherService
 */
class Weather extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'weather.facade';
    }
}
