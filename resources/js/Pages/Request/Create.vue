<script setup>
import AppLayout from "@/Layouts/AppLayout.vue"
import { IconArrowLeft, IconArrowRight, IconHome } from "@tabler/icons-vue"
import {Link, router, usePage} from "@inertiajs/vue3"
const props = defineProps({
    diagnoses: Array,
    patientQuestions: Array,
    organizationQuestions: Array,
    medicalOrganizations: Array,
    selectedDiagnosisId: Number,
});

const selectedDiagnosisId = ref(props.selectedDiagnosisId);
const selectedMedicalOrganizationId = ref(usePage().props.activeDepartment.id);
const patientResponses = ref({});
const organizationResponses = ref({});
const stage = ref('patient'); // Текущий этап: 'organization' или 'patient'

// Фильтрация видимых вопросов для пациента
const visiblePatientQuestions = computed(() => {
    return props.patientQuestions.filter(question => {
        if (!question.depends_on_answer_id) return true; // Если вопрос не зависит от ответа
        return Object.values(patientResponses.value).includes(question.depends_on_answer_id) !== false;
    });
});

// Фильтрация видимых вопросов для МО
const visibleDepartmentQuestions = computed(() => {
    console.log(organizationResponses.value[1])
    return props.organizationQuestions.filter(question => {
        if (!question.depends_on_answer_id) return true; // Если вопрос не зависит от ответа
        return Object.values(organizationResponses.value).includes(question.depends_on_answer_id) !== false;
    });
});

// Индексы текущих вопросов
const currentOrganizationIndex = ref(0);
const currentPatientIndex = ref(0);

// Текущий вопрос для медицинской организации
const currentOrganizationQuestion = computed(() => {
    return visibleDepartmentQuestions.value[currentOrganizationIndex.value];
});

// Текущий вопрос для пациента
const currentPatientQuestion = computed(() => {
    return visiblePatientQuestions.value[currentPatientIndex.value];
});

// Проверка, является ли текущий вопрос последним для медицинской организации
const isLastOrganizationQuestion = computed(() => {
    return currentOrganizationIndex.value === visibleDepartmentQuestions.value.length - 1;
});

// Проверка, является ли текущий вопрос последним для пациента
const isLastPatientQuestion = computed(() => {
    return currentPatientIndex.value === visiblePatientQuestions.value.length - 1;
});

// Прогресс для медицинской организации
const organizationProgress = computed(() => {
    const answered = Object.keys(organizationResponses.value).length;
    const total = visibleDepartmentQuestions.value.length;
    const result = (answered / total) * 100
    return result.toLocaleString(undefined, {
        maximumFractionDigits: 0
    });
});

// Прогресс для пациента
const patientProgress = computed(() => {
    const answered = Object.keys(patientResponses.value).length;
    const total = visiblePatientQuestions.value.length;
    const result = (answered / total) * 100
    return Number(result.toLocaleString(undefined, {
        maximumFractionDigits: 0
    }))
});

// Переход к предыдущему вопросу для медицинской организации
const prevOrganizationQuestion = () => {
    if (currentOrganizationIndex.value > 0) {
        currentOrganizationIndex.value--;
    }
};

// Переход к следующему вопросу для медицинской организации
const nextOrganizationQuestion = () => {
    if (!organizationResponses.value[currentOrganizationQuestion.value.id]) {
        message.error('Пожалуйста, выберите ответ на текущий вопрос.');
        return;
    }

    if (isLastOrganizationQuestion.value) {
        stage.value = 'patient'; // Переход к вопросам для пациента
    } else {
        currentOrganizationIndex.value++;
    }
};

// Переход к предыдущему вопросу для пациента
const prevPatientQuestion = () => {
    if (currentPatientIndex.value > 0) {
        currentPatientIndex.value--;
    }
};

// Переход к следующему вопросу для пациента
const nextPatientQuestion = () => {
    if (!patientResponses.value[currentPatientQuestion.value.id]) {
        window.$message.error('Пожалуйста, выберите ответ на текущий вопрос.');
        return;
    }

    if (isLastPatientQuestion.value) {
        submit(); // Завершение опроса
    } else {
        currentPatientIndex.value++;
    }
};

// Переход к следующему этапу
const nextStage = () => {
    if (stage.value === 'organization') {
        if (Object.keys(organizationResponses.value).length < props.organizationQuestions.length) {
            window.$message.error('Пожалуйста, ответьте на все вопросы медицинской организации.');
            return;
        }
        stage.value = 'patient'; // Переход к вопросам для пациента
    } else {
        submit(); // Завершение опроса
    }
};

const handleAnswerClick = (answer) => {

}

const submit = () => {
    if (!selectedMedicalOrganizationId.value) {
        window.$message.error('Пожалуйста, выберите медицинскую организацию.');
        return;
    }

    if (Object.keys(patientResponses.value).length < visiblePatientQuestions.value.length) {
        window.$message.error('Пожалуйста, ответьте на все вопросы пациента.');
        return;
    }

    router.post(route('request.store'), {
        diagnosis_id: selectedDiagnosisId.value,
        medical_organization_id: selectedMedicalOrganizationId.value,
        patient_responses: patientResponses.value,
        organization_responses: organizationResponses.value,
    }, {
        onSuccess: () => {
            window.$message.success('Ответы успешно отправлены!');
        },
        onError: () => {
            window.$message.error('Произошла ошибка при отправке ответов.');
        }
    })
};

// Отслеживаем изменения ответов на вопросы
watch(patientResponses.value, (newResponses) => {
    // Удаляем ответы на зависимые вопросы, если изменился ответ на предыдущий вопрос
    props.patientQuestions.forEach((question) => {
        if (question.depends_on_answer_id && Object.values(newResponses).includes(question.depends_on_answer_id) === false) {
            delete patientResponses.value[question.id];
        }
    });
}, { deep: true });
</script>
<template>
    <AppLayout>
        <NSpace vertical size="large" class="max-w-xl mx-auto h-full">
            <Link :href="route('workspace')" class="h-full">
                <NButton secondary round>
                    <template #icon>
                        <NIcon :component="IconHome" />
                    </template>
                    Вернуться на рабочую область
                </NButton>
            </Link>

            <!-- Вопросы для медицинской организации -->
<!--            <transition name="fade" mode="out-in">-->
<!--                <NCard v-if="stage === 'organization'" key="organization">-->
<!--                    <template #header>-->
<!--                        {{ currentOrganizationQuestion.text }}-->
<!--                    </template>-->
<!--                    <template #cover>-->
<!--                        <NProgress-->
<!--                            type="line"-->
<!--                            :percentage="organizationProgress"-->
<!--                            :indicator-placement="'inside'"-->
<!--                            status="success"-->
<!--                            border-radius="0 0 3px 3px"-->
<!--                            fill-border-radius="3px"-->
<!--                        />-->
<!--                    </template>-->
<!--                    <NForm>-->
<!--                        <NFormItem-->
<!--                            :key="currentOrganizationQuestion.id"-->
<!--                            :show-label="false"-->
<!--                            :show-feedback="false"-->
<!--                        >-->
<!--                            <NRadioGroup class="flex flex-col gap-y-2" v-model:value="organizationResponses[currentOrganizationQuestion.id]">-->
<!--                                <NRadio-->
<!--                                    v-for="answer in currentOrganizationQuestion.answers"-->
<!--                                    :key="answer.id"-->
<!--                                    :value="answer.id"-->
<!--                                    :label="answer.text"-->
<!--                                />-->
<!--                            </NRadioGroup>-->
<!--                        </NFormItem>-->
<!--                    </NForm>-->
<!--                    <template #action>-->
<!--                        <NButtonGroup class="flex justify-end">-->
<!--                            <NButton secondary-->
<!--                                     :disabled="currentOrganizationIndex === 0"-->
<!--                                     @click="prevOrganizationQuestion">-->
<!--                                <template #icon>-->
<!--                                    <NIcon :component="IconArrowLeft" />-->
<!--                                </template>-->
<!--                                Назад-->
<!--                            </NButton>-->
<!--                            <NButton-->
<!--                                type="primary"-->
<!--                                secondary-->
<!--                                :disabled="!organizationResponses[currentOrganizationQuestion.id]"-->
<!--                                @click="nextOrganizationQuestion"-->
<!--                                icon-placement="right"-->
<!--                            >-->
<!--                                <template #icon>-->
<!--                                    <NIcon :component="IconArrowRight" />-->
<!--                                </template>-->
<!--                                Далее-->
<!--                            </NButton>-->
<!--                        </NButtonGroup>-->
<!--                    </template>-->
<!--                </NCard>-->
<!--            </transition>-->

            <!-- Вопросы для пациента -->
            <transition name="fade" mode="out-in">
                <NCard v-if="stage === 'patient'"
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
                            <NRadioGroup class="flex flex-col gap-y-2" v-model:value="patientResponses[currentPatientQuestion.id]">
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
                                     :disabled="currentPatientIndex === 0"
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
                                :disabled="!patientResponses[currentPatientQuestion.id]"
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
            </transition>
        </NSpace>
    </AppLayout>
</template>

<style scoped>
:deep(.n-card__action) {
    @apply rounded-b-3xl py-4 px-6;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
