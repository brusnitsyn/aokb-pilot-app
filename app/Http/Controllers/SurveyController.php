<?php

namespace App\Http\Controllers;

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
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function show(Request $request)
    {
        $diagnosisGroups = DiagnosisGroup::with('diagnoses')->get();
        $diagnoses = Diagnosis::all(); // Получаем все диагнозы
        $selectedDiagnosis = json_decode(\request()->cookie('selectDiagnosis'));
        $activeDepartment = json_decode(\request()->cookie('activeDepartment'));

        $selectedDiagnosisGroupId = isset($selectedDiagnosis->diagnosis_group_id)
            ? DiagnosisGroup::find($selectedDiagnosis->diagnosis_group_id)->id
            : null; // Выбранная группа диагнозов
        $selectedDiagnosisId = isset($selectedDiagnosis->diagnosis_id)
            ? Diagnosis::find($selectedDiagnosis->diagnosis_id)->id
            : null; // Выбранный диагноз
        $selectedDepartmentId = isset($activeDepartment->id)
            ? Department::find($activeDepartment->id)->id
            : null; // Выбранный диагноз

        // Вопросы для пациента (фильтруем по диагнозу)
        $patientQuestions = $selectedDiagnosisId
            ? Diagnosis::find($selectedDiagnosisId)->questions()->with('answers.scenario')->get()
            : collect();

        // Вопросы для медицинской организации
        $organizationQuestions = DepartmentQuestion::with('answers.departments')->get();

        $departments = DepartmentDiagnosisGroup::with('department')
            ->where('diagnosis_group_id', $selectedDiagnosisGroupId)
            ->get();

        return Inertia::render('Request/Create', [
            'diagnosisGroups' => $diagnosisGroups,
            'diagnoses' => $diagnoses,
            'patientQuestions' => $patientQuestions,
            'organizationQuestions' => $organizationQuestions,
            'selectedDiagnosisId' => $selectedDiagnosisId,
            'selectedDepartmentId' => $selectedDepartmentId,
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'patient' => 'required|array',
            'diagnosis_id' => 'required|exists:diagnoses,id',
            'medical_organization_id' => 'required|exists:departments,id',
            'patient_responses' => 'required|array',
//            'organization_responses' => 'required|array',
        ]);

        $patient = $request->input('patient');
        $selectedDiagnosisId = $request->input('diagnosis_id');
        $patientResponses = $request->input('patient_responses', []);
        $organizationResponses = json_decode($request->cookie('organizationResponses', []));
        $medicalOrganizationId = $request->input('medical_organization_id');

        // Сценарий
        $scenarioId = null;
        $scenarioScore = 0.0; // Баллы сценария
        // Подсчет баллов пациента
        $patientScore = 0;
        foreach ($patientResponses as $questionId => $answerIds) {
            if (is_array($answerIds)) {
                foreach ($answerIds as $answerId) {
                    $answer = Answer::find($answerId);
                    if ($answer) {
                        $patientScore += $answer->score;
                    }
                }
            } else {
                $answer = Answer::find($answerIds)->load(['scenario']);
                // Обработка ответа со сценарием
                if (!is_null($answer->scenario)) {
                    $scenarioId = $answer->scenario->id;
                    $scenarioScore = $answer->scenario->score;
                }
                $patientScore += $answer->score;
            }
        }

        // Подсчет баллов медицинской организации (ответы на вопросы)
        $organizationScore = 0;
        foreach ($organizationResponses as $questionId => $answerIds) {
            if (is_array($answerIds)) {
                foreach ($answerIds as $answerId) {
                    $answer = DepartmentAnswer::find($answerId);
                    if ($answer) {
                        $score = $answer->score;
                        $organizationScore += $score;
                    }
                }
            } else {
                $answer = DepartmentAnswer::find($answerIds);
                if ($answer) {
                    $score = $answer->score;
                    $organizationScore += $score;
                }
            }
        }

        // Добавляем фиксированные баллы медицинской организации
        $department = Department::find($medicalOrganizationId)->load(['params']);
        foreach ($department->params as $param) {
            $organizationScore += $param->paramValue->score;
        }

        // Общий результат
        $totalScore = $patientScore + $organizationScore + $scenarioScore;

        // Определение диагноза (если не был выбран)
        $diagnosis = Diagnosis::find($selectedDiagnosisId);

        // Создание пациента
        $patient = Patient::create(
            [
                'first_name' => $patient['first_name'],
                'last_name' => $patient['last_name'],
                'middle_name' => $patient['middle_name'],
                'date_birth' => $patient['date_birth'],
                'snils' => $patient['snils'],
                'total_score' => $totalScore,
                'diagnosis_id' => $diagnosis->id,
            ]
        );

        // Сохранение результата
        $patientResult = PatientResult::create([
            'patient_id' => $patient->id,
            'department_id' => $medicalOrganizationId,
            'patient_score' => $patientScore,
            'department_score' => $organizationScore,
            'total_score' => $totalScore,
            'patient_responses' => $patientResponses,
            'department_responses' => $organizationResponses,
            'scenario_id' => $scenarioId,
            'scenario_score' => $scenarioScore,
        ]);

        return redirect(route('request.result', [
            'patient_id' => $patient->id
        ]))->cookie('selectDiagnosis', null, config('session.lifetime'));

//        return redirect()->route('survey.result')->with([
//            'totalScore' => $totalScore,
//            'diagnosis' => $diagnosis,
//        ]);
    }

    public function result(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $patientResult = PatientResult::find($request->input('patient_id'))
            ->load([
                'department',
                'department.params',
                'department.params.param',
                'department.params.paramValue',
                'scenario',
                'patient',
                'status'
            ]);
        $diagnosisGroupId = $patientResult->patient->diagnosis->diagnosis_group_id;

        $departmentQuestions = DepartmentQuestion::with('answers.departments')
            ->where('depends_on_diagnosis_group_id', $diagnosisGroupId)
            ->orWhere('depends_on_diagnosis_group_id', null)
            ->get();

        return Inertia::render('Request/Result', [
            'totalScore' => $patientResult->total_score,
            'patientResult' => $patientResult,
            'departmentQuestions' => $departmentQuestions,
        ]);
    }

    public function deleteResult(Request $request)
    {
        $request->validate([
            'patient_result_id'
        ]);

        $patientResult = PatientResult::find($request->input('patient_result_id'))->delete();
        return redirect(route('my.request'));
    }
}
