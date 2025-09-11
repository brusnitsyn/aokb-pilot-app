<?php

namespace App\Exports;

use App\Models\Answer;
use App\Models\DepartmentAnswer;
use App\Models\DepartmentQuestion;
use App\Models\Param;
use App\Models\PatientResult;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AllDataExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $patientQuestions = [];
    protected $departmentQuestions = [];
    protected $departmentParams = [];

    public function __construct()
    {
        // Получаем вопросы пациентов из базы (уникальные по тексту)
        $this->patientQuestions = Question::select(['text', 'id'])
            ->orderBy('id')
            ->get()
            ->unique('text')
            ->pluck('text')
            ->toArray();

        // Получаем вопросы отделений из базы (уникальные по тексту)
        $this->departmentQuestions = DepartmentQuestion::select(['text', 'id'])
            ->orderBy('id')
            ->get()
            ->unique('text')
            ->pluck('text')
            ->toArray();

        $this->departmentParams = Param::get('name')->pluck('name');
    }

    public function collection()
    {
        return PatientResult::with([
            'patient',
            'patient.diagnosis.diagnosisGroup',
            'status',
            'sender_department',
            'from_department',
            'to_department',
            'user',
            'scenario'
        ])->get();
    }

    public function headings(): array
    {
        $headings = [
            'Номер пациента',
            'ФИО',
            'Дата рождения',
            'СНИЛС',
            'Диагноз',
            'Код диагноза',
            'МО откуда',
            'МО куда',
            'Сценарий',
            'Дата создания',
            'Баллы пациента',
            'Баллы отделения',
            'Баллы сценария',
            'Общий балл',
        ];

        // Добавляем вопросы пациентов
        foreach ($this->patientQuestions as $question) {
            $headings[] = $question;
        }

        // Добавляем вопросы отделений
        foreach ($this->departmentQuestions as $question) {
            $headings[] = $question;
        }

        foreach ($this->departmentParams as $param) {
            $headings[] = $param;
        }

        return $headings;
    }

    public function map($result): array
    {
        $patientResponses = $result->getQuestionsWithAnswers();
        $departmentResponses = $result->getQuestionsWithAnswersDepartment();
        $departmentParamsValues = $result->from_department->paramsMapping();

        $baseData = [
            $result->patient->number ?? '',
            $result->patient->full_name ?? '',
            $result->patient->date_birth ? date('d.m.Y', $result->patient->date_birth / 1000) : '',
            $result->patient->snils ?? '',
            $result->patient->diagnosis->name ?? '',
            $result->patient->diagnosis->code ?? '',
            $result->from_department->name ?? '',
            $result->to_department->name ?? '',
            $result->scenario->name ?? '',
            $result->created_at->format('d.m.Y H:i:s'),
            $result->patient_score,
            $result->department_score,
            $result->scenario_score,
            $result->total_score,
        ];

        // Ответы на вопросы пациентов
        $patientAnswers = [];
        foreach ($this->patientQuestions as $question) {
            $patientAnswers[] = $this->findAnswerByQuestionText($question, $patientResponses);
        }

        // Ответы на вопросы МО
        $departmentAnswers = [];
        foreach ($this->departmentQuestions as $question) {
            $departmentAnswers[] = $this->findAnswerByQuestionText($question, $departmentResponses);
        }

        // Параметры МО
        $departmentParams = [];
        foreach ($this->departmentParams as $param) {
            $departmentParams[] = $this->findValueByParamText($param, $departmentParamsValues);
        }

        return array_merge($baseData, $patientAnswers, $departmentAnswers, $departmentParams);
    }

    private function findAnswerByQuestionText($questionText, $responses)
    {
        if (!is_array($responses)) {
            return '';
        }

        foreach ($responses as $key => $response) {
            if ($key === $questionText) {
                // Возвращаем текст ответа, если есть, иначе ID ответа
                return $response ?? '';
            }
        }

        return '';
    }

    private function findValueByParamText($paramText, $responses)
    {
        if (!is_array($responses)) {
            return '';
        }

        foreach ($responses as $key => $response) {
            if ($key === $paramText) {
                // Возвращаем текст ответа, если есть, иначе ID ответа
                return $response ?? '';
            }
        }

        return '';
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => ['font' => ['bold' => true]],
        ];

        // Цветовая маркировка для разных типов вопросов
        $patientQuestionsCount = count($this->patientQuestions);
        $departmentQuestionsCount = count($this->departmentQuestions);
        $departmentParamsCount = count($this->departmentParams);

        // Вопросы пациентов (светло-зеленый)
        if ($patientQuestionsCount > 0) {
            $startCol = 15; // O
            $endCol = $startCol + $patientQuestionsCount - 1;
            $startLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $endLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endCol);

            $styles["{$startLetter}1:{$endLetter}1"] = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E2EFDA']
                ]
            ];
        }

        // Вопросы МО (светло-голубой)
        if ($departmentQuestionsCount > 0) {
            $startCol = 15 + $patientQuestionsCount;
            $endCol = $startCol + $departmentQuestionsCount - 1;
            $startLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $endLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endCol);

            $styles["{$startLetter}1:{$endLetter}1"] = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'DDEBF7']
                ]
            ];
        }

        // Параметры МО (светло-оранжевый)
        if ($departmentParamsCount > 0) {
            $startCol = 15 + $patientQuestionsCount + $departmentQuestionsCount;
            $endCol = $startCol + $departmentParamsCount - 1;
            $startLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
            $endLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endCol);

            $styles["{$startLetter}1:{$endLetter}1"] = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FFA161']
                ]
            ];
        }

        return $styles;
    }
}
