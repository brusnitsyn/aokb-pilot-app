<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static calculateAge($birthDate)
 * @method static float calculateDistance(float $latFrom, float $lonFrom, float $latTo, float $lonTo)
 *
 * @see \App\Services\PatientService
 */
class Patient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'patient.facade';
    }
}
