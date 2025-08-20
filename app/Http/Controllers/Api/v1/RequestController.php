<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PatientResult;
use App\Models\UserDepartment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class RequestController extends Controller
{
    public function index()
    {
        $userDepartments = UserDepartment::where('user_id', auth()->user()->id)
            ->get()
            ->map(function ($item) {
                return $item->department_id;
            })->values()
            ->toArray();

        $patients = PatientResult::with([
            'patient.diagnosis',
            'scenario',
            'status',
            'sender_department',
            'from_department',
            'to_department',
            'user'
        ])->whereIn('from_department_id', $userDepartments)
            ->orWhereIn('to_department_id', $userDepartments)
            ->orderBy('created_at', 'desc')->get();

        $patients->map(function ($patient) {
            if ($patient->from_department_id !== 30)
                $patient->coords = $patient->sender_department->coords;
        });

        $patients = $this->paginate($patients);

        return response()->json([
            'data' => array_values($patients->items()),
            'meta' => [
                'current_page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
                'per_page' => $patients->perPage(),
                'total' => $patients->total(),
            ]
        ]);
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
