<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Diagnosis;
use App\Models\DiagnosisQuestion;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::truncate();
        Answer::truncate();

        $questions = [
            [ 'text' => 'ОКС', 'depends_on_answer_id' => null ], // 1

            [ 'text' => 'Тропонин?', 'depends_on_answer_id' => 1 ], // 2

            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 3 ], // 3
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 3 ], // 4
            [ 'text' => 'Шок', 'depends_on_answer_id' => 4 ], // 5
            [ 'text' => 'Отек', 'depends_on_answer_id' => 4 ], // 6

            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 2 ], // 7
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 2 ], // 8
            [ 'text' => 'Шок', 'depends_on_answer_id' => 12 ], // 9
            [ 'text' => 'Отек', 'depends_on_answer_id' => 12 ], // 10
        ];

        $answers = [
            [ 'text' => 'Без подъема ST', 'score' => 0, 'question_id' => 1 ], // 1 ОКС

            [ 'text' => 'Положительный', 'score' => 0, 'question_id' => 2 ], // 2 Тропонин?
            [ 'text' => 'Отрицательный', 'score' => 0, 'question_id' => 2 ], // 3 Тропонин?

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 3 ], // 4 Нарушение гемодинамики
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 3 ], // 5 Нарушение гемодинамики

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 4 ], // 6 Болевой синдром
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 4 ], // 7 Болевой синдром

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 5 ], // 8 Шок
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 5 ], // 9 Шок

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 6 ], // 10 Отек
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 6 ], // 11 Отек

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 7 ], // 12 Нарушение гемодинамики
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 7 ], // 13 Нарушение гемодинамики

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 9 ], // 14 Шок
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 9 ], // 15 Шок

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 10 ], // 16 Отек
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 10 ], // 17 Отек

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 8 ], // 18 Болевой синдром
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 8 ], // 19 Болевой синдром
        ];

        Question::insert($questions);
        Answer::insert($answers);

        // Связь диагноз - вопрос
        $diagnosis = Diagnosis::all();
        foreach ($diagnosis as $diag) {
            foreach ($questions as $key => $question) {
                DiagnosisQuestion::create([
                    'question_id' => $key + 1,
                    'diagnosis_id' => $diag->id,
                ]);
            }
        }
    }
}
