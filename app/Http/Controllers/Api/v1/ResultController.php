<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PatientResult;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->validate([
            'id' => 'exists:patient_results,id'
        ]);

        $patientResultId = $data['id'];

        $patientResult = PatientResult::find($patientResultId);

        $patientResult = $patientResult->load([
            'patient.diagnosis',
            'scenario',
            'status',
            'sender_department',
            'from_department',
            'to_department',
            'user'
        ]);

        return response()->json($patientResult);
    }
}
