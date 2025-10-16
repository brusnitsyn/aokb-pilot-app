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

    public function store(Request $request)
    {
        // Валидация входных данных
        $data = $request->validate([
            'patient' => 'required|array',
            'diagnosis_id' => 'required|exists:diagnoses,id',
            'medical_organization_id' => 'required|exists:departments,id',
            'from_department_id' => 'required|exists:departments,id',
            'sender_department_id' => 'required|exists:departments,id',
            'patient_responses' => 'required|array',
            'department_responses' => 'required|array',
            'coords' => 'nullable|array',
            'coords_comment' => 'nullable|string',
        ]);

        $patient = $data['patient'];
        $selectedDiagnosisId = $data['diagnosis_id'];
        $patientResponses = $data['patient_responses'];
        $organizationResponses = $data['department_responses'];
        $medicalOrganizationId = $data['medical_organization_id'];

        // Определение диагноза
        $diagnosis = Diagnosis::find($selectedDiagnosisId);

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
        $department = Department::find(auth()->user()->myDepartment()->id)->load('params');
        $departmentParams = $department->params()
            ->whereJsonContains('depends_diagnosis_group_ids', [$selectedDiagnosisId])
            ->whereHas('paramValue', function ($query) use ($selectedDiagnosisId) {
                $query->whereJsonContains('depends_diagnosis_group_ids', [$selectedDiagnosisId]);
            })->get();
        foreach ($departmentParams as $param) {
            $organizationScore += $param->paramValue->score;
        }

        // Общий результат
        $totalScore = $patientScore + $organizationScore + $scenarioScore;

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

        $fromDepartmentId = $data['from_department_id'];
        $fromCoords = $fromDepartmentId === 30 ? $data['coords'] : Department::find($fromDepartmentId)->coords;
        $toCoords = Department::find($medicalOrganizationId)->coords;

        $distance = PatientFacade::calculateDistance(
            $fromCoords[0],
            $fromCoords[1],
            $toCoords[0],
            $toCoords[1],
        );

        // Сохранение результата
        $patientResult = PatientResult::create([
            'patient_id' => $patient->id,
            'sender_department_id' => $data['sender_department_id'],
            'from_department_id' => $data['from_department_id'],
            'coords' => $data['coords'] ?? null,
            'comment' => $data['coords_comment'] ?? null,
            'to_department_id' => $medicalOrganizationId,
            'patient_score' => $patientScore,
            'department_score' => $organizationScore,
            'total_score' => $totalScore,
            'patient_responses' => $patientResponses,
            'department_responses' => $organizationResponses,
            'scenario_id' => $scenarioId,
            'scenario_score' => $scenarioScore,
            'user_id' => auth()->id(),
            'distance' => $distance,
        ]);

        return response()->json([
            'status' => 'created',
            'patient_result_id' => $patientResult->id,
        ]);
    }
}
