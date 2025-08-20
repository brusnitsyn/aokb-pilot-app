<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientResult;
use App\Models\PatientResultResume;
use GuzzleHttp\Client;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GigaChatController extends Controller
{

    private string $templateReport = '
        Ты — медицинский ассистент, который генерирует строго формализованные обоснования для эвакуации пациентов.
        Всегда отвечай в следующем формате:

        ### 1. Диагноз и критичность
        - **Диагноз**: [полное название диагноза + код МКБ]
        - **Критичность**: [низкая/средняя/высокая] (баллы по шкале риска: [X.X])
        - **Ключевые критерии**:
          * [Критерий 1: значение]
          * [Критерий 2: значение]
          * [...]

        ### 2. Основные риски
        - [Риск 1: краткое описание]
        - [Риск 2: краткое описание]
        - [...]

        ### 3. Рекомендации по транспортировке
        - **Предпочтительный способ**: [вертолёт/наземный]
        - **Обоснование**: [2-3 предложения]
        - **Условия транспортировки**:
          * [Требование 1]
          * [Требование 2]
          * [...]

        Пример ввода пользователя:
        "Сгенерируй обоснование для эвакуации. Диагноз: Острый инфаркт миокарда (I21.9). Баллы риска: 22.0. ОКС: с подъемом ST. Нарушения ритма: да."

        Пример твоего ответа:
        ### 1. Диагноз и критичность...
    ';

    private string $resumeTemplate = '
Ты — опытный медицинский ассистент, составляющий развернутое медицинское заключение на основе предоставленных данных.
Строго соблюдай следующие правила:
Запрещено добавлять данные, которых нет в предоставленной информации. Не используй общие фразы типа "стабильное", "норма", "без особенностей".
Если данные по какому-либо пункту отсутствуют, необходимо строго указать: [Данные не предоставлены].
Используй только предоставленную информацию. Не дополняй заключение своими предположениями или шаблонными формулировками.

## **1. Основные клинические данные**
- Полный диагноз:
  - Основной: [название] (код МКБ-10: [код])
  - Сопутствующие: [перечислить с кодами]
- Шкала риска:
  - Баллы: [баллы]
  - Интерпретация: [развернутый анализ]
- Степень срочности:
  - Категория 1 (экстренная): требуется вмешательство <90 мин
  - Категория 2 (срочная): требуется вмешательство <24 ч
  - Категория 3 (плановая): возможно отсроченное лечение

## **2. Детальная клиническая картина**
- Временные параметры:
  - Дебют симптомов: [дата/время]
  - Динамика развития: [постепенное/острое]
- Характеристика основных симптомов:
  - [Подробное описание каждого симптома]
  - Шкала боли (если применимо): [VAS/NRS]

- Объективный статус:
  - Гемодинамика:
    - АД: [значение] (стабильность)
    - ЧСС: [значение] (ритмичность)
    - SpO2: [значение]
  - Физикальные данные:
    - [Легочные, сердечные, неврологические и др.]

## **3. Комплексный план ведения**

- Неотложные мероприятия:
  - Медикаментозная терапия:
    - Антитромбоцитарная: [препараты, дозы, схема]
    - Антикоагулянтная: [препараты, дозы]
    - Симптоматическая: [обезболивание, седация и др.]
  - Инвазивная стратегия:
    - Показания: [обоснование]
    - Сроки выполнения: [экстренно/срочно]
    - Вид вмешательства: [ЧКВ, АКШ и др.]

- Мониторинг:
  - Параметры: [перечень с частотой контроля]
  - Критерии эффективности: [ожидаемая динамика]
  - Тревожные признаки: [симптомы ухудшения]

- Дальнейшее наблюдение:
  - Консультации специалистов: [кардиохирург, аритмолог и др.]
  - Реабилитация: [этапы и сроки]
  - Прогноз: [кратко- и долгосрочный]

## **Заключение**
[Обобщающая оценка состояния]
    ';

    public function generate()
    {
//        $token = $this->getToken();

        $patient = Patient::first();
        $patientResult = PatientResult::first();
        $diagnosis = $patient->diagnosis;
        $scenario = $patientResult->scenario;
        $patientQuestionsWithAnswers = $patientResult->getQuestionsWithAnswers();
        $additionalInfo = '';
        foreach ($patientQuestionsWithAnswers as $question => $answer) {
            $additionalInfo .= "$question: $answer\n";
        }

        $completion = "
            Сгенерируй обоснование для эвакуации.
            Диагноз: $diagnosis->name ($diagnosis->code).
            Баллы риска: $patientResult->total_score.
            Предложенный сценарий: $scenario->name.
            Дополнительная информация о пациенте:
            $additionalInfo
        ";

        $messageRequest = [
            'model' => 'GigaChat-2-Max',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->templateReport
                ],
                [
                    'role' => 'user',
                    'content' => $completion
                ]
            ],
            'profanity_check' => true
        ];

        dd($completion);
        $promise = Http::async()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->withBody(json_encode($messageRequest))
            ->post(config('gigachat.urls.completions'));

        $response = $promise->wait();
        $data = $response->json();

        return $data;
    }

    public function resumePatient(Request $request)
    {
        $patientResultId = $request->get('patient_result_id', null);

        if (is_null($patientResultId)) {
            return;
        }

        $patientResultResume = PatientResultResume::where('patient_result_id', $patientResultId)->first();
        if ($patientResultResume) {
            return $patientResultResume;
        }

        $patientResult = PatientResult::find($patientResultId);
        $patientQuestionsWithAnswers = $patientResult->getQuestionsWithAnswers();
        $departmentQuestionsWithAnswers = $patientResult->getQuestionsWithAnswersDepartment();
        $patient = $patientResult->patient;
        $diagnosis = $patient->diagnosis;
        $scenario = $patientResult->scenario;
        $additionalInfo = '';
        $departmentInfo = '';
        foreach ($patientQuestionsWithAnswers as $question => $answer) {
            $additionalInfo .= "$question: $answer\n";
        }
        foreach ($departmentQuestionsWithAnswers as $question => $answer) {
            $departmentInfo .= "$question: $answer\n";
        }

        $completion = "
Резюмируй пациента:
Диагноз: $diagnosis->name ($diagnosis->code).
Баллы риска: $patientResult->total_score.
Предложенный сценарий: $scenario->name.
Дополнительная информация о пациенте:
$additionalInfo
Информация о МО:
$departmentInfo
        ";

        $messageRequest = [
            'model' => 'GigaChat-2-Max',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->resumeTemplate
                ],
                [
                    'role' => 'user',
                    'content' => $completion
                ]
            ],
            'profanity_check' => true
        ];

        $token = $this->getToken();
        $promise = Http::async()
            ->timeout(300)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer $token"
            ])
            ->withBody(json_encode($messageRequest))
            ->post(config('gigachat.urls.completions'));

        $response = $promise->wait();
        $data = $response->json();

        $content = $data['choices'][0]['message']['content'];

        $patientResultResume = PatientResultResume::updateOrCreate(
            ['patient_result_id' => $patientResultId],
            ['content' => $content, 'patient_result_id' => $patientResultId]
        );

        return $patientResultResume->toArray();
    }

    public function getToken() : string
    {
        $token = Cache::get('giga_client_token');
        if (!is_null($token)) {
            return $token;
        }

        $appToken = config('gigachat.token');
        $promise = Http::async()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'RqUID' => Str::uuid()->toString(),
                'Authorization' => "Basic $appToken"
            ])
            ->asForm()
            ->post(config('gigachat.urls.auth'), [
                'scope' => config('gigachat.scope'),
            ]);

        // Ждем ответа...
        $response = $promise->wait();
        Log::info($response);
        $data = $response->json();

        $token = Cache::put('giga_client_token', $data['access_token'], 1800);
        return $token;
    }
}
