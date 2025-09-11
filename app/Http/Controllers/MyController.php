<?php

namespace App\Http\Controllers;

use App\Exports\AllDataExport;
use App\Facades\Weather;
use App\Models\PatientResult;
use App\Models\UserDepartment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class MyController extends Controller
{
    public function requests(Request $request)
    {
        $department = json_decode($request->cookie('activeDepartment'));

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

//            $weather = Weather::weatherCachedByCoordinate($patient->coords[1], $patient->coords[0]);
//            $patient->weather = $weather;
        });

        $patients = $this->paginate($patients);

        return Inertia::render('My/Requests/Show', [
            'patients' => [...$patients->items()],
            'lastPage' => $patients->lastPage(),
        ]);
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function update(Request $request)
    {
        $patientResultId = $request->input('patient_result_id');

        $patientResult = PatientResult::find($patientResultId);

        $patientResult->update([
            'status_id' => 2
        ]);

        return redirect(route('my.request'));
    }

    public function export(Request $request)
    {
        return Excel::download(new AllDataExport(), 'Регистр пилот.xlsx');
    }
}
