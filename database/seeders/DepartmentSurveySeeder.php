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
            [ 'text' => 'Оснащение', 'depends_on_answer_id' => null ], // 1

            [ 'text' => 'Не в полном объеме (отсутствуют позиции)', 'depends_on_answer_id' => 2 ], // 2

            [ 'text' => 'Возможность эвакуации собственными силами', 'depends_on_answer_id' => null ], // 3

            [ 'text' => 'Возможность посадки воздушного судна', 'depends_on_answer_id' => 8 ], // 4

            [ 'text' => 'Возможность оказания помощи на месте', 'depends_on_answer_id' => null ], // 5

            [ 'text' => 'Отсутствие специалистов', 'depends_on_answer_id' => null ], // 6
        ];

        $answers = [
            [ 'text' => 'Отсутствует', 'score' => 4.5, 'department_question_id' => 1 ], // 1 Оснащение
            [ 'text' => 'Не в полном объеме', 'score' => 0, 'department_question_id' => 1 ], // 2 Оснащение
            [ 'text' => 'В полном объеме', 'score' => 0, 'department_question_id' => 1 ], // 3 Оснащение

            [ 'text' => 'ЭКГ (дист)', 'score' => 2, 'department_question_id' => 2 ], // 4 Не в полном объеме (отсутствуют позиции)
            [ 'text' => 'УЗИ', 'score' => 1, 'department_question_id' => 2 ], // 5 Не в полном объеме (отсутствуют позиции)
            [ 'text' => 'ЭКС', 'score' => 0.5, 'department_question_id' => 2 ], // 6 Не в полном объеме (отсутствуют позиции)
            [ 'text' => 'ИВЛ', 'score' => 1, 'department_question_id' => 2 ], // 7 Не в полном объеме (отсутствуют позиции)

            [ 'text' => 'Отсутствует', 'score' => 0, 'department_question_id' => 3 ], // 8 Возможность эвакуации собственными силами
            [ 'text' => 'Имеется', 'score' => 0, 'department_question_id' => 3 ], // 9 Возможность эвакуации собственными силами

            [ 'text' => 'Отсутствует', 'score' => 0, 'department_question_id' => 4 ], // 10 Возможность посадки воздушного судна
            [ 'text' => 'Затруднительна', 'score' => 0, 'department_question_id' => 4 ], // 11 Возможность посадки воздушного судна
            [ 'text' => 'Имеется', 'score' => 0.5, 'department_question_id' => 4 ], // 12 Возможность посадки воздушного судна

            [ 'text' => 'Имеется', 'score' => 0, 'department_question_id' => 5 ], // 13 Возможность оказания помощи на месте
            [ 'text' => 'Отсутствует', 'score' => 0.5, 'department_question_id' => 5 ], // 14 Возможность оказания помощи на месте

            [ 'text' => 'Кардиолог', 'score' => 0.5, 'department_question_id' => 6 ], // 15 Отсутствие специалистов
            [ 'text' => 'Анестезиолог-реаниматолог', 'score' => 0.5, 'department_question_id' => 6 ], // 16 Отсутствие специалистов
            [ 'text' => 'Терапевт', 'score' => 0.5, 'department_question_id' => 6 ], // 17 Отсутствие специалистов
            [ 'text' => 'Фельдшер', 'score' => 0.5, 'department_question_id' => 6 ], // 18 Отсутствие специалистов
        ];

        DepartmentQuestion::insert($questions);
        DepartmentAnswer::insert($answers);
    }
}
