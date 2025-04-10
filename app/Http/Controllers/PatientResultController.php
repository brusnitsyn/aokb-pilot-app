<?php

namespace App\Http\Controllers;

use App\Models\PatientResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientResultController extends Controller
{
    public function show(PatientResult $patientResult)
    {
        $patientResult = $patientResult->load([
            'patient.diagnosis',
            'scenario',
            'status',
            'sender_department',
            'from_department',
            'to_department',
            'user'
        ]);

        return Inertia::render('Patient/Results/Show', [
            'patientResult' => $patientResult
        ]);
    }

    public function update(Request $request, PatientResult $patientResult)
    {
        $data = $request->validate([
            'status_id' => 'required|exists:patient_result_statuses,id',
        ], [
            'status_id.required' => 'Установите новый статус',
        ]);

        $patientResult->update($data);
    }
}
