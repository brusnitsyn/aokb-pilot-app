<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Department;
use App\Models\DepartmentAnswer;
use App\Models\DepartmentQuestion;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\PatientResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function show(Request $request)
    {
        $diagnoses = Diagnosis::all(); // Получаем все диагнозы
        $selectedDiagnosisId = $request->input('diagnosis_id'); // Выбранный диагноз

        // Вопросы для пациента (фильтруем по диагнозу)
        $patientQuestions = $selectedDiagnosisId
            ? Diagnosis::find($selectedDiagnosisId)->questions()->with('answers')->get()
            : collect();

        // Вопросы для медицинской организации
        $organizationQuestions = DepartmentQuestion::with('answers.departments')->get();

        return Inertia::render('Request/Create', [
            'diagnoses' => $diagnoses,
            'patientQuestions' => $patientQuestions,
            'organizationQuestions' => $organizationQuestions,
            'selectedDiagnosisId' => $selectedDiagnosisId,
        ]);
    }

    public function store(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'diagnosis_id' => 'required|exists:diagnoses,id',
            'medical_organization_id' => 'required|exists:departments,id',
            'patient_responses' => 'required|array',
//            'organization_responses' => 'required|array',
        ]);

        $selectedDiagnosisId = $request->input('diagnosis_id');
        $patientResponses = $request->input('patient_responses', []);
        $organizationResponses = json_decode($request->cookie('organizationResponses', []));
        $medicalOrganizationId = $request->input('medical_organization_id');

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
                $answer = Answer::find($answerIds);
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
        $totalScore = $patientScore + $organizationScore;

        // Определение диагноза (если не был выбран)
        $diagnosis = Diagnosis::find($selectedDiagnosisId);

        // Создание пациента
        $patient = Patient::create(
            [
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
                'patient'
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
