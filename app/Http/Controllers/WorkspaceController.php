<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentAnswer;
use App\Models\DepartmentQuestion;
use App\Models\Diagnosis;
use App\Models\DiagnosisGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkspaceController extends Controller
{
    public function show(Request $request)
    {
        $diagnosisGroups = DiagnosisGroup::with('diagnoses')->get();
        $departments = Department::all();

        // Вопросы для медицинской организации
        $organizationQuestions = DepartmentQuestion::with('answers.departments')->get();

        // Cookie
        $activeDepartment = json_decode(\request()->cookie('activeDepartment'));
        $organizationResponses = json_decode(\request()->cookie('organizationResponses'));
        $selectedDiagnosis = json_decode(\request()->cookie('selectDiagnosis'));

        // Группа диагноза и сам диагноз
        $selectedDiagnosisGroup = isset($selectedDiagnosis->diagnosisGroupId) ? DiagnosisGroup::with('diagnoses')->find($selectedDiagnosis->diagnosisGroupId) : null;
        $selectedDiagnosis = isset($selectedDiagnosis->diagnosisId) ? Diagnosis::find($selectedDiagnosis->diagnosisId) : null;

        return Inertia::render('Workspace', [
            'diagnosisGroups' => $diagnosisGroups,
            'departments' => $departments,
            'activeDepartment' => $activeDepartment,
            'organizationQuestions' => $organizationQuestions,
            'organizationResponses' => $organizationResponses,
            'selectedDiagnosisGroup' => $selectedDiagnosisGroup,
            'selectedDiagnosis' => $selectedDiagnosis,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
//            'medical_organization_id' => 'required|exists:departments,id',
            'organization_responses' => 'required|array',
        ]);

        $organizationResponses = $request->input('organization_responses', []);
//        $medicalOrganizationId = $request->input('medical_organization_id');

        return response('')
            ->cookie('organizationResponses',
                json_encode($organizationResponses),
                config('session.lifetime')
            );
    }

    public function setDiagnosis(Request $request)
    {
        $request->validate([
            'diagnosisGroupId' => 'required|numeric',
            'diagnosisId' => 'required|numeric',
        ]);

        $diagnosisGroup = DiagnosisGroup::findOrFail($request->input('diagnosisGroupId'));
        $diagnosis = Diagnosis::findOrFail($request->input('diagnosisId'));

        return response('')
            ->cookie('selectDiagnosis',
                json_encode(['diagnosisGroupId' => $diagnosisGroup->id, 'diagnosisId' => $diagnosis->id]),
                config('session.lifetime')
            );
    }
}
