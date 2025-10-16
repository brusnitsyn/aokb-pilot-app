<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\DepartmentQuestion;
use App\Models\Patient;
use App\Models\PatientResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResultController extends Controller
{
    public function show(Request $request)
    {
        $data = $request->validate([
            'id' => 'exists:patient_results,id'
        ]);

        $patientResultId = $data['id'];

        $patientResult = PatientResult::find($patientResultId);

        $patient = $patientResult->patient;
        $diagnosisGroupId = $patient->diagnosis->diagnosis_group_id;

        $patientResult = PatientResult::with([
            'from_department',
            'to_department',
            'scenario',
            'patient.diagnosis',
            'status',
            'from_department.params' => function ($query) use ($diagnosisGroupId) {
                $query->whereJsonContains('depends_diagnosis_group_ids', $diagnosisGroupId);
            },
            'from_department.params.param',
            'from_department.params.paramValue' => function ($query) use ($diagnosisGroupId) {
                $query->whereJsonContains('depends_diagnosis_group_ids', $diagnosisGroupId);
            },
        ])->where('patient_id', $patient->id)->first();

        $departmentQuestions = DepartmentQuestion::with('answers.departments')
            ->whereHas('dependsDiagnosisGroup', function ($query) use ($diagnosisGroupId) {
                $query->where('diagnosis_group_id', $diagnosisGroupId);
            })
            ->get();

        $questionResponses = $patientResult->patient_responses;

        $patientQuestions = [];
        foreach ($questionResponses as $questionId => $answerId) {
            $question = Question::find($questionId);
            $answer  = Answer::find($answerId);
            $patientQuestions[] = ['question' => $question, 'answer' => $answer];
        }

//        return Inertia::render('Request/Result', [
//            'totalScore' => $patientResult->total_score,
//            'patientResult' => $patientResult,
//            'departmentQuestions' => $departmentQuestions,
//            'senderDepartment' => auth()->user()->departments->first(),
//            'patientQuestions' => $patientQuestions,
//        ]);

        return response()->json([
            'totalScore' => $patientResult->total_score,
            'patientResult' => $patientResult,
            'departmentQuestions' => $departmentQuestions,
            'senderDepartment' => auth()->user()->departments->first(),
            'patientQuestions' => $patientQuestions,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $data = $request->validate([
            'status_id' => 'required|exists:patient_result_statuses,id',
            'patient_result_id' => 'required|exists:patient_results,id',
        ], [
            'status_id.required' => 'Установите новый статус',
            'patient_result_id.required' => 'Укажите ИД результата для обновления статуса',
        ]);

        $patientResult = PatientResult::find($data['patient_result_id']);

        $patientResult->update([
            'status_id' => $data['status_id'],
        ]);
    }

    public function delete(Request $request, $id)
    {
        $patientResult = PatientResult::find($id)->delete();
        return response()->json([
            'status' => 'deleted',
        ]);
    }
}
