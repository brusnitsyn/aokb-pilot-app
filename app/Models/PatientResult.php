<?php

namespace App\Models;

use App\Observers\PatientResultObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(PatientResultObserver::class)]
class PatientResult extends Model
{
    protected $fillable = [
        'patient_id',
        'sender_department_id',
        'from_department_id',
        'coords',
        'comment',
        'to_department_id',
        'patient_score',
        'department_score',
        'total_score',
        'patient_responses',
        'department_responses',
        'scenario_id',
        'scenario_score',
        'status_id',
        'user_id',
        'last_status_at',
        'status_changed_at',
        'distance'
    ];

    public function sender_department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'sender_department_id');
    }

    public function from_department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    public function to_department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function scenario(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Scenario::class);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PatientResultStatus::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getQuestionsWithAnswers()
    {
        // Декодируем JSON из БД
        $questionAnswerPairs = $this->patient_responses->toArray();

        // Получаем ID вопросов и ответов
        $questionIds = array_keys($questionAnswerPairs);
        $answerIds = array_values($questionAnswerPairs);

        // Загружаем вопросы и ответы одним запросом (оптимизация)
        $questions = Question::whereIn('id', $questionIds)->pluck('text', 'id');
        $answers = Answer::whereIn('id', $answerIds)->pluck('text', 'id');

        // Собираем результат: [ "Текст вопроса" => "Текст ответа" ]
        $result = [];
        foreach ($questionAnswerPairs as $questionId => $answerId) {
            $questionText = $questions[$questionId] ?? 'Вопрос не найден';
            $answerText = $answers[$answerId] ?? 'Ответ не найден';
            $result[$questionText] = $answerText;
        }

        return $result;
    }

    public function getQuestionsWithAnswersDepartment()
    {
        // Декодируем JSON из БД
        $questionAnswerPairs = $this->department_responses->toArray();

        // Получаем ID вопросов и ответов
        $questionIds = [];
        $answerIds = [];

        foreach ($questionAnswerPairs as $questionId => $answerIdOrArray) {
            $questionIds[] = $questionId;

            if (is_array($answerIdOrArray)) {
                $answerIds = array_merge($answerIds, $answerIdOrArray);
            } else {
                $answerIds[] = $answerIdOrArray;
            }
        }

        // Загружаем вопросы и ответы одним запросом (оптимизация)
        $questions = DepartmentQuestion::whereIn('id', $questionIds)->pluck('text', 'id');
        $answers = DepartmentAnswer::whereIn('id', $answerIds)->pluck('text', 'id');

        // Собираем результат: [ "Текст вопроса" => "Текст ответа" ]
        $result = [];
        foreach ($questionAnswerPairs as $questionId => $answerIdOrArray) {
            $questionText = $questions[$questionId] ?? 'Вопрос не найден';

            if (is_array($answerIdOrArray)) {
                $answerTexts = [];
                foreach ($answerIdOrArray as $answerId) {
                    $answerTexts[] = $answers[$answerId] ?? 'Ответ не найден';
                }
                $result[$questionText] = implode(', ', $answerTexts);
            } else {
                $result[$questionText] = $answers[$answerIdOrArray] ?? 'Ответ не найден';
            }
        }

        return $result;
    }

    protected function casts(): array
    {
        return [
            'patient_responses' => AsArrayObject::class,
            'department_responses' => AsArrayObject::class,
            'coords' => AsArrayObject::class,
        ];
    }
}
