<?php

namespace App\Http\Controllers;

use App\Models\PatientResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyController extends Controller
{
    public function requests(Request $request)
    {
        $department = json_decode($request->cookie('activeDepartment'));
        $patients = PatientResult::with([
            'patient.diagnosis',
            'scenario'
        ])->where('department_id', $department->id)->get();

        return Inertia::render('My/Requests/Show', [
            'patients' => $patients,
        ]);
    }
}
