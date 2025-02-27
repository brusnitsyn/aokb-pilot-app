<script setup>
import {IconArrowLeft, IconArrowRight} from "@tabler/icons-vue"
import {Motion} from 'motion-v'

const props = defineProps(['stage', 'patientQuestions'])
const { onPrevStage, onNextStage, onSubmit } = inject('navigate')
const model = defineModel('patientResponses')
const _currentScenario = ref(null)
const currentScenario = computed({
    get() {
        if (model.value === null) return null
        const keys = Object.keys(model.value)
        if (keys.length === 0) return null
        const lastKey = Number(keys[keys.length - 1])
        const lastValue = Number(model.value[lastKey])

        const question = props.patientQuestions.find(item => item.id === lastKey)
        const ans = question.answers.find(item => item.id === lastValue)

        if (ans.scenario !== null) {
            return _currentScenario.value = ans.scenario
        } else if (_currentScenario.value !== null && (question.id === _currentScenario.value.answer.question_id)) {
            return _currentScenario.value = null
        } else if (_currentScenario.value === null) {
            return _currentScenario.value = null
        } else {
            return _currentScenario.value
        }
    }
})

// Фильтрация видимых вопросов для пациента
const visiblePatientQuestions = computed(() => {
    return props.patientQuestions.filter(question => {
        if (!question.depends_on_answer_id) return true; // Если вопрос не зависит от ответа
        return Object.values(model.value).includes(question.depends_on_answer_id) !== false;
    });
});

// Индексы текущих вопросов
const currentPatientIndex = ref(0);

// Текущий вопрос для пациента
const currentPatientQuestion = computed(() => {
    return visiblePatientQuestions.value[currentPatientIndex.value];
});

// Проверка, является ли текущий вопрос последним для пациента
const isLastPatientQuestion = computed(() => {
    return currentPatientIndex.value === visiblePatientQuestions.value.length - 1;
});

// Прогресс для пациента
const patientProgress = computed(() => {
    const answered = Object.keys(model.value).length;
    const total = visiblePatientQuestions.value.length;
    const result = (answered / total) * 100
    return Number(result.toLocaleString(undefined, {
        maximumFractionDigits: 0
    }))
});

// Переход к предыдущему вопросу для пациента
const prevPatientQuestion = () => {
    if (currentPatientIndex.value > 0) {
        currentPatientIndex.value--;
    } else {
        onPrevStage()
    }
};

// Переход к следующему вопросу для пациента
const nextPatientQuestion = () => {
    if (!model.value[currentPatientQuestion.value.id]) {
        window.$message.error('Пожалуйста, выберите ответ на текущий вопрос.');
        return;
    }

    if (isLastPatientQuestion.value) {
        onSubmit(); // Завершение опроса
    } else {
        currentPatientIndex.value++;
    }
};

const handleAnswerClick = (answer) => {

}

// Отслеживаем изменения ответов на вопросы
watch(model.value, (newResponses) => {
    // Удаляем ответы на зависимые вопросы, если изменился ответ на предыдущий вопрос
    props.patientQuestions.forEach((question) => {
        if (question.depends_on_answer_id
            && Object.values(newResponses).includes(question.depends_on_answer_id) === false)
        {
            delete model.value[question.id];
        }
    });
}, { deep: true });
</script>

<template>
    <Motion
        v-if="stage === 'patient-questions'"
        key="patient-questions"
        :initial="{ y: 100, opacity: 0 }"
        :animate="{ y: 0, opacity: 1 }"
        :exit="{ y: 100, opacity: 0 }">
        <NCard v-if="stage === 'patient-questions' && patientQuestions.length > 0"
               key="patient"
               class="!rounded-3xl drop-shadow-sm"
        >
            <template #header>
                {{ currentPatientQuestion.text }}
            </template>
            <template #cover>
                <NProgress
                    type="line"
                    :percentage="patientProgress"
                    color="#EC6608"
                    :indicator-placement="'inside'"
                    status="success"
                    height="24px"
                    border-radius="24px 24px 0px 0px"
                    :fill-border-radius="patientProgress >= 100 ? '0px 24px 0px 0px' : '0px 24px 24px 0px'"
                />
            </template>
            <NForm>
                <NFormItem
                    :key="currentPatientQuestion.id"
                    :show-label="false"
                    :show-feedback="false"
                >
                    <NRadioGroup class="flex flex-col gap-y-2" v-model:value="model[currentPatientQuestion.id]">
                        <NRadio
                            v-for="answer in currentPatientQuestion.answers"
                            :key="answer.id"
                            :value="answer.id"
                            :label="answer.text"
                            @click="handleAnswerClick(answer)"
                        />
                    </NRadioGroup>
                </NFormItem>
            </NForm>
            <template #action>
                <NButtonGroup class="flex justify-end">
                    <NButton secondary
                             round
                             @click="prevPatientQuestion">
                        <template #icon>
                            <NIcon :component="IconArrowLeft" />
                        </template>
                        Назад
                    </NButton>
                    <NButton
                        type="primary"
                        round
                        secondary
                        icon-placement="right"
                        :disabled="!model[currentPatientQuestion.id]"
                        @click="nextPatientQuestion"
                    >
                        <template #icon>
                            <NIcon :component="IconArrowRight" />
                        </template>
                        {{ isLastPatientQuestion ? 'Завершить' : 'Далее' }}
                    </NButton>
                </NButtonGroup>
            </template>
        </NCard>
        <NCard v-else-if="stage === 'patient-questions' && patientQuestions.length === 0"
               class="!rounded-3xl drop-shadow-sm"
        >
            Для выбранного диагноза еще не подготовлены вопросы
        </NCard>
    </Motion>

    <Motion
        v-if="currentScenario !== null"
        :initial="{ y: 100 }"
        :animate="{ y: 0, scale: 1 }"
        :exit="{ y: -100, scale: 0 }">
        <NAlert class="!rounded-3xl drop-shadow-sm bg-[#fbeef1]" type="info">
            <div class="leading-5">
                Текущий сценарий &mdash; <span class="font-medium">{{ currentScenario.name }}</span>
            </div>
        </NAlert>
    </Motion>
</template>

<style scoped>
:deep(.n-alert__icon) {
    @apply text-[#d03050];
}
:deep(.n-alert__border) {
    border: 1px solid #f3cbd3;
}
:deep(.n-card__action) {
    @apply rounded-b-3xl py-4 px-6;
}
</style>
