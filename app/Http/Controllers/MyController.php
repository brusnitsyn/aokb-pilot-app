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
            'scenario',
            'status'
        ])->orderBy('created_at', 'desc')->get();

        return Inertia::render('My/Requests/Show', [
            'patients' => $patients,
        ]);
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
}
