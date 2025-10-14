<?php

namespace App\Http\Controllers\Api\v1;

use App\Facades\Patient as PatientFacade;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Department;
use App\Models\DepartmentAnswer;
use App\Models\DepartmentDiagnosisGroup;
use App\Models\DepartmentQuestion;
use App\Models\Diagnosis;
use App\Models\DiagnosisGroup;
use App\Models\Patient;
use App\Models\PatientResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function show(Request $request)
    {
        $diagnosisGroups = DiagnosisGroup::with('diagnoses')->get();
        $diagnoses = Diagnosis::all(); // Получаем все диагнозы
        $selectedDiagnosisGroupId = $request->query('diagnosis_group_id'); // Входит diagnosis_group_id и diagnosis_id
        $selectedDiagnosisId = $request->query('diagnosis_id'); // Входит diagnosis_group_id и diagnosis_id
//        $myDepartmentId = json_decode(\request()->cookie('myDepartment'));

        $selectedDiagnosisGroupId = isset($selectedDiagnosisGroupId)
            ? DiagnosisGroup::find($selectedDiagnosisGroupId)->id
            : null; // Выбранная группа диагнозов
        $selectedDiagnosisId = isset($selectedDiagnosisId)
            ? Diagnosis::find($selectedDiagnosisId)->id
            : null; // Выбранный диагноз

        $selectedDepartmentId = auth()->user()->myDepartment()->id;

        // Вопросы для пациента (фильтруем по диагнозу)
        $patientQuestions = $selectedDiagnosisId
            ? Diagnosis::find($selectedDiagnosisId)->questions()->with('answers.scenario.answer')->get()
            : collect();

        // Вопросы для медицинской организации
        $organizationQuestions = DepartmentQuestion::with([
            'answers' => function ($query) use ($selectedDepartmentId, $selectedDiagnosisGroupId) {
                $query->with('dependsDiagnosisGroup')->whereHas('dependsDiagnosisGroup', function ($query) use ($selectedDiagnosisGroupId) {
                    $query->where('diagnosis_group_id', $selectedDiagnosisGroupId);
                })->whereJsonDoesntContain('disabled_department_ids', $selectedDepartmentId)
                    ->orderBy('text');
            },
            'answers.departments'
        ])->orderBy('id')
            ->get();

        $departments = DepartmentDiagnosisGroup::with('department')
            ->whereNot('department_id', $selectedDepartmentId)
            ->where('diagnosis_group_id', $selectedDiagnosisGroupId)
            ->whereHas('department', function ($query) {
                $query->where('is_receive', true);
            })
            ->get();

        return response()->json([
            'diagnosisGroups' => $diagnosisGroups,
            'diagnoses' => $diagnoses,
            'patientQuestions' => $patientQuestions,
            'organizationQuestions' => $organizationQuestions,
            'selectedDiagnosisId' => $selectedDiagnosisId,
            'selectedDepartmentId' => $selectedDepartmentId,
            'departments' => $departments
        ]);
    }
}
