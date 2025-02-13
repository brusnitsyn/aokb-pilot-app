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

            [ 'text' => 'ТЛТ', 'depends_on_answer_id' => 20 ], // 11

            [ 'text' => 'ТЛТ - Да', 'depends_on_answer_id' => 21 ], // 12

            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 24 ], // 13
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 24 ], // 14
            [ 'text' => 'Положительная динамика на ЭКГ?', 'depends_on_answer_id' => 24 ], // 15

            [ 'text' => 'Шок', 'depends_on_answer_id' => 25 ], // 16 Не эффективный - Нарушение гемодинамики - Да
            [ 'text' => 'Отек', 'depends_on_answer_id' => 25 ], // 17 Не эффективный - Нарушение гемодинамики - Да

            [ 'text' => 'ТЛТ - Нет', 'depends_on_answer_id' => 22 ], // 18 Тлт - Нет

            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 35 ], // 19 Тлт - нет - До 48 часов от начала заболевания
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 35 ], // 20 Тлт - нет - До 48 часов от начала заболевания
            [ 'text' => 'Положительная динамика на ЭКГ?', 'depends_on_answer_id' => 35 ], // 21 Тлт - нет - До 48 часов от начала заболевания

            [ 'text' => 'Шок', 'depends_on_answer_id' => 37 ], // 22 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики - Да
            [ 'text' => 'Отек', 'depends_on_answer_id' => 37 ], // 23 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики - Да

            [ 'text' => 'Нарушение гемодинамики', 'depends_on_answer_id' => 36 ], // 24 Тлт - нет - После 48 часов от начала заболевания
            [ 'text' => 'Болевой синдром', 'depends_on_answer_id' => 36 ], // 25 Тлт - нет - После 48 часов от начала заболевания
            [ 'text' => 'Положительная динамика на ЭКГ?', 'depends_on_answer_id' => 36 ], // 26 Тлт - нет - После 48 часов от начала заболевания

            [ 'text' => 'Шок', 'depends_on_answer_id' => 47 ], // 27 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики - Да
            [ 'text' => 'Отек', 'depends_on_answer_id' => 47 ], // 28 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики - Да
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

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 11, 'scenario_id' => null ], // 21 ТЛТ
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 11, 'scenario_id' => null ], // 22 ТЛТ

            [ 'text' => 'Эффективный', 'score' => 0, 'question_id' => 12, 'scenario_id' => null ], // 23 ТЛТ - Да
            [ 'text' => 'Не эффективный', 'score' => 0, 'question_id' => 12, 'scenario_id' => null ], // 24 ТЛТ - Да

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 13, 'scenario_id' => null ], // 25 Не эффективный - Нарушение гемодинамики
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 13, 'scenario_id' => null ], // 26 Не эффективный - Нарушение гемодинамики

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 16, 'scenario_id' => null ], // 27 Не эффективный - Нарушение гемодинамики - Да - Шок
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 16, 'scenario_id' => null ], // 28 Не эффективный - Нарушение гемодинамики - Да - Шок

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 17, 'scenario_id' => null ], // 29 Не эффективный - Нарушение гемодинамики - Да - Отек
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 17, 'scenario_id' => null ], // 30 Не эффективный - Нарушение гемодинамики - Да - Отек

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 14, 'scenario_id' => null ], // 31 Не эффективный - Болевой синдром
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 14, 'scenario_id' => null ], // 32 Не эффективный - Болевой синдром

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 15, 'scenario_id' => null ], // 33 Не эффективный - Положительная динамика на ЭКГ?
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 15, 'scenario_id' => null ], // 34 Не эффективный - Положительная динамика на ЭКГ?

            [ 'text' => 'До 48 часов от начала заболевания', 'score' => 0, 'question_id' => 18, 'scenario_id' => null ], // 35 Тлт - нет
            [ 'text' => 'После 48 часов от начала заболевания', 'score' => 0, 'question_id' => 18, 'scenario_id' => null ], // 36 Тлт - нет

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 19, 'scenario_id' => null ], // 37 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 19, 'scenario_id' => null ], // 38 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 22, 'scenario_id' => null ], // 39 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики - Да - Шок
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 22, 'scenario_id' => null ], // 40 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики - Да - Шок

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 23, 'scenario_id' => null ], // 41 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики - Да - Отек
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 23, 'scenario_id' => null ], // 42 Тлт - нет - До 48 часов от начала заболевания - Нарушение гемодинамики - Да - Отек

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 20, 'scenario_id' => null ], // 43 Тлт - нет - До 48 часов от начала заболевания - Болевой синдром
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 20, 'scenario_id' => null ], // 44 Тлт - нет - До 48 часов от начала заболевания - Болевой синдром

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 21, 'scenario_id' => null ], // 45 Тлт - нет - До 48 часов от начала заболевания - Положительная динамика на ЭКГ?
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 21, 'scenario_id' => null ], // 46 Тлт - нет - До 48 часов от начала заболевания - Положительная динамика на ЭКГ?

            // Тлт - нет - После 48 часов от начала заболевания
            [ 'text' => 'Да', 'score' => 0, 'question_id' => 24, 'scenario_id' => null ], // 47 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 24, 'scenario_id' => null ], // 48 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 27, 'scenario_id' => null ], // 49 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики - Да - Шок
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 27, 'scenario_id' => null ], // 50 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики - Да - Шок

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 28, 'scenario_id' => null ], // 51 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики - Да - Отек
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 28, 'scenario_id' => null ], // 52 Тлт - нет - После 48 часов от начала заболевания - Нарушение гемодинамики - Да - Отек

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 25, 'scenario_id' => null ], // 53 Тлт - нет - После 48 часов от начала заболевания - Болевой синдром
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 25, 'scenario_id' => null ], // 54 Тлт - нет - После 48 часов от начала заболевания - Болевой синдром

            [ 'text' => 'Да', 'score' => 0, 'question_id' => 26, 'scenario_id' => null ], // 55 Тлт - нет - После 48 часов от начала заболевания - Положительная динамика на ЭКГ?
            [ 'text' => 'Нет', 'score' => 0, 'question_id' => 26, 'scenario_id' => null ], // 56 Тлт - нет - После 48 часов от начала заболевания - Положительная динамика на ЭКГ?
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
