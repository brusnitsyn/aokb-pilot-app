<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentAnswer;
use App\Models\DepartmentQuestion;
use App\Models\Diagnosis;
use App\Models\DiagnosisGroup;
use App\Models\PatientResult;
use App\Models\UserDepartment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkspaceController extends Controller
{
    public function show(Request $request)
    {
        $diagnosisGroups = DiagnosisGroup::with('diagnoses')->get();
        $departments = Department::where('is_receive', true)->get();

        $userDepartments = auth()->user()->departments->map(function ($item) {
            return $item->department_id;
        })->values()->toArray();


        $selectedDiagnosis = json_decode(\request()->cookie('selectDiagnosis'));


        // Группа диагноза и сам диагноз
        $selectedDiagnosisGroup = isset($selectedDiagnosis->diagnosisGroupId) ? DiagnosisGroup::with('diagnoses')->find($selectedDiagnosis->diagnosisGroupId) : null;
        $selectedDiagnosis = isset($selectedDiagnosis->diagnosisId) ? Diagnosis::find($selectedDiagnosis->diagnosisId) : null;

        if (count($userDepartments) > 1)
            $countResults = PatientResult::whereIn('from_department_id', $userDepartments)->count();
        else
            $countResults = PatientResult::where('from_department_id', $userDepartments)->count();

        return Inertia::render('Workspace', [
            'diagnosisGroups' => $diagnosisGroups,
            'departments' => $departments,
//            'activeDepartment' => $activeDepartment,
//            'organizationQuestions' => $organizationQuestions,
//            'organizationResponses' => $organizationResponses,
            'selectedDiagnosisGroup' => $selectedDiagnosisGroup,
            'selectedDiagnosis' => $selectedDiagnosis,
            'countResults' => $countResults,
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
            'diagnosis_group_id' => 'required|numeric',
            'diagnosis_id' => 'required|numeric',
        ]);

        $diagnosisGroup = DiagnosisGroup::findOrFail($request->input('diagnosis_group_id'));
        $diagnosis = Diagnosis::findOrFail($request->input('diagnosis_id'));

        return response('')
            ->cookie('selectDiagnosis',
                json_encode(['diagnosis_group_id' => $diagnosisGroup->id, 'diagnosis_id' => $diagnosis->id]),
                config('session.lifetime')
            )->cookie('organizationResponses', null, config('session.lifetime'));
    }
}
