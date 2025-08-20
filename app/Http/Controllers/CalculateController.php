<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalculateController extends Controller
{
    public function calculateEvacuation(Request $request)
    {
        // Получаем координаты госпиталя (можно вынести в конфиг)
        $hospitalCoords = [127.506377,50.304888]; // Пример: [долгота, широта]
        // Получаем пациентов из БД
        $patients = Patient::query()
            ->has('results')
            ->whereHas('results', function ($query) use ($request) {
                $query->where('status_id', 2);
            })
            ->get()
            ->map(function (Patient $patient) {
                $result = $patient->results()->latest()->first();

                // Проверка отделения на Вылет по координатам
                if ($result->from_department_id !== 30) $coords = $result->from_department->coords;
                else $coords = $result->coords;

                return [
                    'id' => $patient->id,
                    'coords' => $coords,
                    'priority' => $patient->total_score,
                    'time_created' => Carbon::createFromTimestampMs($result->status_changed_at)
                ];
            })
            ->toArray();

        // Рассчитываем маршрут
        $route = $this->findOptimalRoute($patients, $hospitalCoords);

        // Возвращаем результат
        return response()->json([
            'success' => true,
            'route' => $route['path'],
            'distance_km' => round($route['distance'], 2),
            'patients' => array_map(function ($p) {
                return ['id' => $p['id'], 'priority' => $p['priority']];
            }, $route['patients'])
        ]);
    }

    private function findOptimalRoute(array $patients, array $hospitalCoords, int $numPatients = 3, float $minEvacuationDistance = 0.5) : array
    {
        $filteredPatients = array_filter($patients, function ($patient) use ($hospitalCoords, $minEvacuationDistance) {
            $distance = $this->haversine(
                $hospitalCoords[0],
                $hospitalCoords[1],
                $patient['coords'][0],
                $patient['coords'][1]
            );
            return $distance >= $minEvacuationDistance;
        });

        // Сортируем пациентов по приоритету (по убыванию) и времени создания (по возрастанию)
        usort($filteredPatients, function ($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return strtotime($a['time_created']) <=> strtotime($b['time_created']);
            }
            return $b['priority'] <=> $a['priority'];
        });

        // Берем N самых критичных пациентов
        $criticalPatients = array_slice($filteredPatients, 0, $numPatients);

        // Алгоритм ближайшего соседа для построения маршрута
        $currentPoint = $hospitalCoords;
        $route = [$hospitalCoords];
        $totalDistance = 0;
        $orderedPatients = [];
        $remainingPatients = $criticalPatients;

        while (!empty($remainingPatients)) {
            // Находим ближайшего пациента
            $nearest = null;
            $minDistance = PHP_FLOAT_MAX;

            foreach ($remainingPatients as $patient) {
                $distance = $this->haversine(
                    $currentPoint[0],
                    $currentPoint[1],
                    $patient['coords'][0],
                    $patient['coords'][1]
                );

                // Обработка сегмента отрезка
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $nearest = $patient;
                }
            }

            // Добавляем в маршрут
            $totalDistance += $minDistance;
            $route[] = $nearest['coords'];
            $orderedPatients[] = $nearest;
            $currentPoint = $nearest['coords'];

            // Удаляем из оставшихся
            $remainingPatients = array_filter($remainingPatients, function ($p) use ($nearest) {
                return $p['id'] !== $nearest['id'];
            });
        }

        // Возвращаемся в госпиталь
        $distanceBack = $this->haversine(
            $currentPoint[0],
            $currentPoint[1],
            $hospitalCoords[0],
            $hospitalCoords[1]
        );
        $totalDistance += $distanceBack;
        $route[] = $hospitalCoords;

        return [
            'path' => $route,
            'distance' => $totalDistance,
            'segments' => $orderedPatients
        ];
    }

    private function haversine(float $lon1, float $lat1, float $lon2, float $lat2): float
    {
        $earthRadius = 6371; // км

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            ));

        return $angle * $earthRadius;
    }
}
