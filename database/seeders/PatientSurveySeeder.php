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

            [ 'text' => 'Жизнеугрожающие нарушения ритма', 'depends_on_answer_id' => 2 ], // 3
            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 2 ], // 4
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 2 ], // 5
            [ 'text' => 'Ишемические изменения на ЭКГ', 'depends_on_answer_id' => 2 ], // 6

            [ 'text' => 'Жизнеугрожающие нарушения ритма', 'depends_on_answer_id' => 3 ], // 7
            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 3 ], // 8
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 3 ], // 9
            [ 'text' => 'Ишемические изменения на ЭКГ', 'depends_on_answer_id' => 3 ], // 10

            [ 'text' => 'Время от начала заболевания', 'depends_on_answer_id' => 20 ], // 11

            [ 'text' => 'ТЛТ', 'depends_on_answer_id' => 21 ], // 12

            [ 'text' => 'Жизнеугрожающие нарушения ритма', 'depends_on_answer_id' => 24 ], // 13
            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 24 ], // 14
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 24 ], // 15
            [ 'text' => 'Ишемические изменения на ЭКГ', 'depends_on_answer_id' => 24 ], // 16

            // ТЛТ не проведен
            [ 'text' => 'Жизнеугрожающие нарушения ритма', 'depends_on_answer_id' => 25 ], // 17
            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 25 ], // 18
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 25 ], // 19
            [ 'text' => 'Ишемические изменения на ЭКГ', 'depends_on_answer_id' => 25 ], // 20

            // От 12 до 48 часов от начала заболевания
            [ 'text' => 'Жизнеугрожающие нарушения ритма', 'depends_on_answer_id' => 22 ], // 21
            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 22 ], // 22
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 22 ], // 23
            [ 'text' => 'Ишемические изменения на ЭКГ', 'depends_on_answer_id' => 22 ], // 24

            // Больше 48 часов от начала заболевания
            [ 'text' => 'Жизнеугрожающие нарушения ритма', 'depends_on_answer_id' => 23 ], // 25
            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 23 ], // 26
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 23 ], // 27
            [ 'text' => 'Ишемические изменения на ЭКГ', 'depends_on_answer_id' => 23 ], // 28
        ];

        $answers = [
            [ 'text' => 'Без подъема ST', 'score' => 0, 'question_id' => 1, 'scenario_id' => null ], // 1 ОКС

            [ 'text' => 'Положительный', 'score' => 0, 'question_id' => 2, 'scenario_id' => 1 ], // 2 Тропонин?
            [ 'text' => 'Отрицательный', 'score' => 0, 'question_id' => 2, 'scenario_id' => 2 ], // 3 Тропонин?

            // Положительные
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 3, 'scenario_id' => null ], // 4 Жизнеугрожающие нарушения ритма
            [ 'text' => 'Да', 'score' => 3, 'question_id' => 3, 'scenario_id' => null ], // 5 Жизнеугрожающие нарушения ритма

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 4, 'scenario_id' => null ], // 6 Нарушение гемодинамики
            [ 'text' => 'Да', 'score' => 2, 'question_id' => 4, 'scenario_id' => null ], // 7 Нарушение гемодинамики

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 5, 'scenario_id' => null ], // 8 Болевой синдром
            [ 'text' => 'Да', 'score' => 1, 'question_id' => 5, 'scenario_id' => null ], // 9 Болевой синдром

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 6, 'scenario_id' => null ], // 10 Ишемические изменения на ЭКГ
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 6, 'scenario_id' => null ], // 11 Ишемические изменения на ЭКГ

            // Отрицательные
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 7, 'scenario_id' => null ], // 12 Жизнеугрожающие нарушения ритма
            [ 'text' => 'Да', 'score' => 3, 'question_id' => 7, 'scenario_id' => null ], // 13 Жизнеугрожающие нарушения ритма

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 8, 'scenario_id' => null ], // 14 Нарушение гемодинамики
            [ 'text' => 'Да', 'score' => 2, 'question_id' => 8, 'scenario_id' => null ], // 15 Нарушение гемодинамики

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 9, 'scenario_id' => null ], // 16 Болевой синдром
            [ 'text' => 'Да', 'score' => 1, 'question_id' => 9, 'scenario_id' => null ], // 17 Болевой синдром

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 10, 'scenario_id' => null ], // 18 Ишемические изменения на ЭКГ
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 10, 'scenario_id' => null ], // 19 Ишемические изменения на ЭКГ


            [ 'text' => 'С подъемом ST', 'score' => 0, 'question_id' => 1, 'scenario_id' => null ], // 20 ОКС

            [ 'text' => 'До 12 часов от начала заболевания', 'score' => 0, 'question_id' => 11, 'scenario_id' => null ], // 21 Время от начала заболевания
            [ 'text' => 'От 12 до 48 часов от начала заболевания', 'score' => 0, 'question_id' => 11, 'scenario_id' => 5 ], // 22 Время от начала заболевания
            [ 'text' => 'Больше 48 часов от начала заболевания', 'score' => 0, 'question_id' => 11, 'scenario_id' => 6 ], // 23 Время от начала заболевания

            [ 'text' => 'Проведен', 'score' => 0, 'question_id' => 12, 'scenario_id' => 4 ], // 24 ТЛТ
            [ 'text' => 'Не проведен', 'score' => 0, 'question_id' => 12, 'scenario_id' => 3 ], // 25 ТЛТ

            // ТЛТ проведен
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 13, 'scenario_id' => null ], // 26 Жизнеугрожающие нарушения ритма
            [ 'text' => 'Да', 'score' => 3, 'question_id' => 13, 'scenario_id' => null ], // 27 Жизнеугрожающие нарушения ритма

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 14, 'scenario_id' => null ], // 28 Нарушение гемодинамики
            [ 'text' => 'Да', 'score' => 2, 'question_id' => 14, 'scenario_id' => null ], // 29 Нарушение гемодинамики

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 15, 'scenario_id' => null ], // 30 Болевой синдром
            [ 'text' => 'Да', 'score' => 1, 'question_id' => 15, 'scenario_id' => null ], // 31 Болевой синдром

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 16, 'scenario_id' => null ], // 32 Ишемические изменения на ЭКГ
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 16, 'scenario_id' => null ], // 33 Ишемические изменения на ЭКГ

            // ТЛТ проведен
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 17, 'scenario_id' => null ], // 34 Жизнеугрожающие нарушения ритма
            [ 'text' => 'Да', 'score' => 3, 'question_id' => 17, 'scenario_id' => null ], // 35 Жизнеугрожающие нарушения ритма

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 18, 'scenario_id' => null ], // 36 Нарушение гемодинамики
            [ 'text' => 'Да', 'score' => 2, 'question_id' => 18, 'scenario_id' => null ], // 37 Нарушение гемодинамики

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 19, 'scenario_id' => null ], // 38 Болевой синдром
            [ 'text' => 'Да', 'score' => 1, 'question_id' => 19, 'scenario_id' => null ], // 39 Болевой синдром

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 20, 'scenario_id' => null ], // 40 Ишемические изменения на ЭКГ
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 20, 'scenario_id' => null ], // 41 Ишемические изменения на ЭКГ

            // От 12 до 48 часов от начала заболевания
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 21, 'scenario_id' => null ], // 42 Жизнеугрожающие нарушения ритма
            [ 'text' => 'Да', 'score' => 3, 'question_id' => 21, 'scenario_id' => null ], // 43 Жизнеугрожающие нарушения ритма

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 22, 'scenario_id' => null ], // 44 Нарушение гемодинамики
            [ 'text' => 'Да', 'score' => 2, 'question_id' => 22, 'scenario_id' => null ], // 45 Нарушение гемодинамики

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 23, 'scenario_id' => null ], // 46 Болевой синдром
            [ 'text' => 'Да', 'score' => 1, 'question_id' => 23, 'scenario_id' => null ], // 47 Болевой синдром

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 24, 'scenario_id' => null ], // 48 Ишемические изменения на ЭКГ
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 24, 'scenario_id' => null ], // 49 Ишемические изменения на ЭКГ

            // Больше 48 часов от начала заболевания
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 25, 'scenario_id' => null ], // 50 Жизнеугрожающие нарушения ритма
            [ 'text' => 'Да', 'score' => 3, 'question_id' => 25, 'scenario_id' => null ], // 51 Жизнеугрожающие нарушения ритма

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 26, 'scenario_id' => null ], // 52 Нарушение гемодинамики
            [ 'text' => 'Да', 'score' => 2, 'question_id' => 26, 'scenario_id' => null ], // 53 Нарушение гемодинамики

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 27, 'scenario_id' => null ], // 54 Болевой синдром
            [ 'text' => 'Да', 'score' => 1, 'question_id' => 27, 'scenario_id' => null ], // 55 Болевой синдром

            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 28, 'scenario_id' => null ], // 56 Ишемические изменения на ЭКГ
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 28, 'scenario_id' => null ], // 57 Ишемические изменения на ЭКГ
        ];

        Answer::insert($answers);
        Question::insert($questions);

        // Связь диагноз ОКС - вопрос
        $diagnosis = Diagnosis::where("code", 'like', 'I%')->get();
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
