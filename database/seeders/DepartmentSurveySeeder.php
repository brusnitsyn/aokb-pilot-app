<?php

namespace Database\Seeders;

use App\Models\DepartmentAnswer;
use App\Models\DepartmentQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DepartmentQuestion::truncate();
        DepartmentAnswer::truncate();

        $questions = [
            [
                'text' => 'Оснащение',
                'depends_on_answer_id' => null,
                'depends_on_diagnosis_group_id' => null,
                'type' => 'single',
                'requires_confirmation' => false,
                'requires' => true,
                'default_answers' => null,
                'default_answer' => 3
            ], // 1
            [
                'text' => 'Не в полном объеме (отсутствуют позиции)',
                'depends_on_answer_id' => 2,
                'depends_on_diagnosis_group_id' => 1,
                'type' => 'multiple',
                'requires_confirmation' => true,
                'requires' => true,
                'default_answers' => null,
                'default_answer' => null
            ], // 2
            [
                'text' => 'Возможность эвакуации собственными силами',
                'depends_on_answer_id' => null,
                'depends_on_diagnosis_group_id' => null,
                'type' => 'single',
                'requires_confirmation' => false,
                'requires' => true,
                'default_answers' => null,
                'default_answer' => null
            ], // 3
            [
                'text' => 'Возможность посадки воздушного судна',
                'depends_on_answer_id' => 8,
                'depends_on_diagnosis_group_id' => null,
                'type' => 'single',
                'requires_confirmation' => false,
                'requires' => true,
                'default_answers' => null,
                'default_answer' => null
            ], // 4
            [
                'text' => 'Возможность оказания помощи на месте',
                'depends_on_answer_id' => null,
                'depends_on_diagnosis_group_id' => null,
                'type' => 'single',
                'requires_confirmation' => false,
                'requires' => true,
                'default_answers' => null,
                'default_answer' => null
            ], // 5
            [
                'text' => 'Отсутствие специалистов',
                'depends_on_answer_id' => null,
                'depends_on_diagnosis_group_id' => 1,
                'type' => 'multiple',
                'requires_confirmation' => true,
                'requires' => false,
                'default_answers' => null,
                'default_answer' => null
            ], // 6
        ];

        $answers = [
            [
                'text' => 'Отсутствует',
                'score' => 3.5,
                'department_question_id' => 1,
                'disabled_department_ids' => "[30]"
            ], // 1 Оснащение
            [
                'text' => 'Не в полном объеме',
                'score' => 0,
                'department_question_id' => 1,
                'disabled_department_ids' => "[]"
            ], // 2 Оснащение
            [
                'text' => 'В полном объеме',
                'score' => 0,
                'department_question_id' => 1,
                'disabled_department_ids' => "[]"
            ], // 3 Оснащение

            [
                'text' => 'ЭКГ (дист)',
                'score' => 1,
                'department_question_id' => 2,
                'disabled_department_ids' => "[]"
            ], // 4 Не в полном объеме (отсутствуют позиции)
            [
                'text' => 'УЗИ',
                'score' => 1,
                'department_question_id' => 2,
                'disabled_department_ids' => "[2,3,4,5,6,7,8,9,10,11,12,13,19,20,21,22,23,24,25,26,27,28,29,30]"
            ], // 5 Не в полном объеме (отсутствуют позиции)
            [
                'text' => 'ЭКС',
                'score' => 0.5,
                'department_question_id' => 2,
                'disabled_department_ids' => "[2,3,4,5,6,7,8,9,10,11,12,13,19,20,21,22,23,24,25,26,27,28,29,30]"
            ], // 6 Не в полном объеме (отсутствуют позиции)
            [
                'text' => 'ИВЛ',
                'score' => 1,
                'department_question_id' => 2,
                'disabled_department_ids' => "[2,3,4,5,6,7,8,9,10,11,12,13,19,20,21,22,23,24,25,26,27,28,29,30]"
            ], // 7 Не в полном объеме (отсутствуют позиции)

            [
                'text' => 'Отсутствует',
                'score' => 0,
                'department_question_id' => 3,
                'disabled_department_ids' => "[]"
            ], // 8 Возможность эвакуации собственными силами
            [
                'text' => 'Имеется',
                'score' => 0,
                'department_question_id' => 3,
                'disabled_department_ids' => "[]"
            ], // 9 Возможность эвакуации собственными силами

            [
                'text' => 'Отсутствует',
                'score' => 1.5,
                'department_question_id' => 4,
                'disabled_department_ids' => "[]"
            ], // 10 Возможность посадки воздушного судна
            [
                'text' => 'Затруднительна',
                'score' => 1,
                'department_question_id' => 4,
                'disabled_department_ids' => "[]"
            ], // 11 Возможность посадки воздушного судна
            [
                'text' => 'Имеется',
                'score' => 0.5,
                'department_question_id' => 4,
                'disabled_department_ids' => "[]"
            ], // 12 Возможность посадки воздушного судна

            [
                'text' => 'Имеется',
                'score' => 0,
                'department_question_id' => 5,
                'disabled_department_ids' => "[]"
            ], // 13 Возможность оказания помощи на месте
            [
                'text' => 'Отсутствует',
                'score' => 0.5,
                'department_question_id' => 5,
                'disabled_department_ids' => "[]"
            ], // 14 Возможность оказания помощи на месте

            [
                'text' => 'Кардиолог',
                'score' => 1,
                'department_question_id' => 6,
                'disabled_department_ids' => "[2,3,4,5,6,7,8,9,10,11,12,13,19,20,21,22,23,24,25,26,27,28,29,30]"
            ], // 15 Отсутствие специалистов
            [
                'text' => 'Анестезиолог-реаниматолог',
                'score' => 1,
                'department_question_id' => 6,
                'disabled_department_ids' => "[2,3,4,5,6,7,8,9,10,11,12,13,19,20,21,22,23,24,25,26,27,28,29,30]"
            ], // 16 Отсутствие специалистов
            [
                'text' => 'Терапевт',
                'score' => 1,
                'department_question_id' => 6,
                'disabled_department_ids' => "[1,7,8,9,10,11,12,13,14,15,16,17,18,22,23,24,25,26,27,28,29,30]"
            ], // 17 Отсутствие специалистов
            [
                'text' => 'Фельдшер',
                'score' => 1,
                'department_question_id' => 6,
                'disabled_department_ids' => "[1,2,3,4,5,6,14,15,16,17,21]"
            ], // 18 Отсутствие специалистов
        ];

        DepartmentQuestion::insert($questions);
        DepartmentAnswer::insert($answers);
    }
}
