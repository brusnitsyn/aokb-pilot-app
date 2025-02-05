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
            'organization_responses' => 'required|array',
        ]);

        $selectedDiagnosisId = $request->input('diagnosis_id');
        $patientResponses = $request->input('patient_responses', []);
        $organizationResponses = $request->input('organization_responses', []);
        $medicalOrganizationId = $request->input('medical_organization_id');

        // Подсчет баллов пациента
        $patientScore = 0;
        foreach ($patientResponses as $questionId => $answerId) {
            $answer = Answer::find($answerId);
            $patientScore += $answer->score;
        }

        // Подсчет баллов медицинской организации (ответы на вопросы)
        $organizationScore = 0;
        foreach ($organizationResponses as $questionId => $answerId) {
            $answer = DepartmentAnswer::find($answerId);
            //$score = $answer->departments->find($medicalOrganizationId)->pivot->score;
            $score = $answer->score;
            $organizationScore += $score;
        }

        // Добавляем фиксированные баллы медицинской организации
//        $medicalOrganization = Department::find($medicalOrganizationId);
//        $organizationScore += $medicalOrganization->parameter_1_score
//            + $medicalOrganization->parameter_2_score
//            + $medicalOrganization->parameter_3_score;

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
        PatientResult::create([
            'patient_id' => $patient->id,
            'department_id' => $medicalOrganizationId,
            'patient_score' => $patientScore,
            'department_score' => $organizationScore,
            'total_score' => $totalScore,
        ]);

        return Inertia::render('Request/Result', [
            'totalScore' => $totalScore,
        ]);

//        return redirect()->route('survey.result')->with([
//            'totalScore' => $totalScore,
//            'diagnosis' => $diagnosis,
//        ]);
    }

    public function result()
    {

    }
}
