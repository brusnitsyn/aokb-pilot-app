<?php

namespace App\Services;

use App\Models\PatientResult;

class PatientService
{
    public function calculateAge($birthDate)
    {

    }

    public function calculateDistance(float $latFrom, float $lonFrom, float $latTo, float $lonTo) : float
    {
        $earthRadius = 6371; // радиус земли

        $latFrom = deg2rad($latFrom);
        $lonFrom = deg2rad($lonFrom);
        $latTo = deg2rad($latTo);
        $lonTo = deg2rad($lonTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            ));

        return $angle * $earthRadius;
    }
}
