<script setup>
import {IconArrowLeft, IconArrowRight} from "@tabler/icons-vue";
import {router, usePage} from "@inertiajs/vue3";
import {Motion} from 'motion-v'
const props = defineProps(['stage', 'validationMessage', 'departmentQuestions'])
const { onPrevStage, onNextStage, hasPrevStage } = inject('navigate')
const model = ref({})

const page = usePage()
const diagnosisGroupId = ref(null)
const diagnosisId = ref(null)

// Инициализация ответов со значениями по умолчанию
const initializeResponses = () => {
    props.departmentQuestions.forEach((question) => {
        if (question.type === 'multiple' && question.default_answers) {
            model.value[question.id] = question.default_answers;
        }
        if (question.type === 'single' && question.default_answer) {
            model.value[question.id] = question.default_answer;
        }
    });
}

initializeResponses()

// Индекс текущего вопроса
const currentOrganizationIndex = ref(0);

// Фильтрация видимых вопросов для МО
const visibleDepartmentQuestions = computed(() => {
    return props.departmentQuestions.filter(question => {
        if (!question.depends_on_answer_id) return true; // Если вопрос не зависит от ответа
        return Object.values(model.value).includes(question.depends_on_answer_id) !== false;
    });
});

// Текущий вопрос для медицинской организации
const currentOrganizationQuestion = computed(() => {
    return visibleDepartmentQuestions.value[currentOrganizationIndex.value];
});

// Проверка, является ли текущий вопрос последним для медицинской организации
const isLastOrganizationQuestion = computed(() => {
    return currentOrganizationIndex.value === visibleDepartmentQuestions.value.length - 1;
});

// Прогресс для медицинской организации
const progress = computed(() => {
    const answered = Object.keys(model.value).filter(itm => {
        const currentQuestion = visibleDepartmentQuestions.value.find(vdq => vdq.id === Number(itm))
        if (Array.isArray(model.value[itm])) {
            if (model.value[itm].length > 0 && currentQuestion.requires)
                return true
            else
                return false
        } else {
            if (model.value[itm] !== null && currentQuestion.requires)
                return true
        }
    }).length;
    const total = visibleDepartmentQuestions.value
        .filter(item => item.requires === true).length;
    const result = (answered / total) * 100
    return Number(result.toLocaleString(undefined, {
        maximumFractionDigits: 0
    }))
});

// Переход к предыдущему вопросу для медицинской организации
const prevOrganizationQuestion = () => {
    if (currentOrganizationIndex.value > 0) {
        currentOrganizationIndex.value--;
    } else {
        onPrevStage()
    }
};

// Переход к следующему вопросу для медицинской организации
const nextOrganizationQuestion = () => {
    if (currentOrganizationQuestion.value.requires === true && !model.value[currentOrganizationQuestion.value.id]) {
        window.$message.error('Пожалуйста, выберите ответ на текущий вопрос.');
        return;
    }

    if (isLastOrganizationQuestion.value) {
        onSubmit()
    } else {
        currentOrganizationIndex.value++;
    }
};

const handlePositiveClick = (question, answer) => {
    if (question.type === 'single') {
        model.value[question.id] = answer.id
    } else {
        if (!model.value[question.id]) {
            model.value[question.id] = []
        }
        if (model.value[question.id].includes(answer.id) === false)
            model.value[question.id].push(answer.id)
        else {
            const index = model.value[question.id].indexOf(answer.id);
            if (index !== -1) {
                model.value[question.id].splice(index, 1);
            }
        }
    }
}

const handleNegativeClick = (question, answer) => {
    if (question.type === 'single') {
        model.value[question.id] = null
    } else {
        if (!model.value[question.id]) {
            model.value[question.id] = []
        }
    }
}

const hasDisableNext = computed(() => {
    if (currentOrganizationQuestion.value.type === 'multiple') {
        if (model.value[currentOrganizationQuestion.value.id]
            && model.value[currentOrganizationQuestion.value.id].length === 0
            && currentOrganizationQuestion.value.requires) {
            return true
        }
    }
    return !model.value[currentOrganizationQuestion.value.id] && currentOrganizationQuestion.value.requires
})

// Отслеживаем изменения ответов на вопросы
watch(model.value, (newResponses) => {
    // Удаляем ответы на зависимые вопросы, если изменился ответ на предыдущий вопрос
    props.departmentQuestions.forEach((question) => {
        if (question.depends_on_answer_id && Object.values(newResponses).includes(question.depends_on_answer_id) === false) {
            delete model.value[question.id];
        }
        // Удаление пустого массива
        if (!question.requires && question.type === 'multiple') {
            if (model.value[question.id]?.length === 0) {
                delete model.value[question.id];
            }
        }
    });
}, { deep: true });


const onSubmit = async () => {
    await axios.post(route('workspace.post'), {
        organization_responses: model.value
    }).then(() => {
        onNextStage()
        router.reload()
    })
}
</script>

<template>
    <Motion
        v-if="stage === 'department-questions'"
        key="department-questions"
        :initial="{ y: 100, opacity: 0 }"
        :animate="{ y: 0, opacity: 1 }"
        :exit="{ y: 100, opacity: 0 }">
        <NCard class="!rounded-3xl drop-shadow-sm">
            <template #header>
                {{ currentOrganizationQuestion.text }}
            </template>
            <template #cover>
                <NProgress
                    type="line"
                    :percentage="progress"
                    color="#EC6608"
                    :indicator-placement="'inside'"
                    status="success"
                    height="24px"
                    border-radius="24px 24px 0px 0px"
                    :fill-border-radius="progress >= 100 ? '0px 24px 0px 0px' : '0px 24px 24px 0px'"
                />
            </template>
            <NForm class="flex flex-col gap-y-3">
                <NFormItem
                    :key="currentOrganizationQuestion.id"
                    :show-label="false"
                    :show-feedback="false"
                >
                    <NRadioGroup v-if="currentOrganizationQuestion.type === 'single'"
                                 class="flex flex-col gap-y-2"
                                 :value="model[currentOrganizationQuestion.id]">
                        <template v-if="currentOrganizationQuestion.requires_confirmation"
                                  v-for="answer in currentOrganizationQuestion.answers"
                                  :key="`confirmed-${answer.id}-single`">
                            <NPopconfirm
                                @positive-click="handlePositiveClick(currentOrganizationQuestion, answer)"
                                @negative-click="handleNegativeClick(currentOrganizationQuestion, answer)"
                                :negative-text="null"
                                placement="top-start"
                            >
                                <template #trigger>
                                    <NRadio :value="answer.id"
                                            :label="answer.text"
                                    />
                                </template>
                                Подтвердите изменение
                            </NPopconfirm>
                        </template>
                        <NRadio v-else
                                v-for="answer in currentOrganizationQuestion.answers"
                                :key="answer.id"
                                @click="handlePositiveClick(currentOrganizationQuestion, answer)"
                                :value="answer.id"
                                :label="answer.text"
                        />
                    </NRadioGroup>
                    <NCheckboxGroup
                        v-else
                        :value="model[currentOrganizationQuestion.id]"
                        class="flex flex-col gap-y-2"
                    >
                        <template v-if="currentOrganizationQuestion.requires_confirmation"
                                  v-for="answer in currentOrganizationQuestion.answers"
                                  :key="`confirmed-${answer.id}`">
                            <NPopconfirm
                                @positive-click="handlePositiveClick(currentOrganizationQuestion, answer)"
                                @negative-click="handleNegativeClick(currentOrganizationQuestion, answer)"
                                @clickoutside="handleNegativeClick(currentOrganizationQuestion, answer)"
                                :negative-text="null"
                                placement="top-start"
                            >
                                <template #trigger>
                                    <NCheckbox :value="answer.id"
                                               :label="answer.text"
                                    />
                                </template>
                                Подтвердите изменение
                            </NPopconfirm>
                        </template>
                        <NCheckbox
                            v-else
                            v-for="answer in currentOrganizationQuestion.answers"
                            :key="answer.id"
                            :value="answer.id"
                            :label="answer.text"
                        />
                    </NCheckboxGroup>
                </NFormItem>
            </NForm>
            <template #action>
                <NButtonGroup class="flex justify-end">
                    <NButton v-if="hasPrevStage"
                             secondary
                             round
                             @click="prevOrganizationQuestion">
                        <template #icon>
                            <NIcon :component="IconArrowLeft" />
                        </template>
                        Назад
                    </NButton>
                    <NButton
                        type="primary"
                        secondary
                        round
                        :disabled="hasDisableNext"
                        @click="nextOrganizationQuestion"
                        icon-placement="right"
                    >
                        <template #icon>
                            <NIcon :component="IconArrowRight" />
                        </template>
                        Далее
                    </NButton>
                </NButtonGroup>
            </template>
        </NCard>
    </Motion>

    <Motion
        v-if="stage === 'department-questions' && progress !== 100"
        :initial="{ y: 100 }"
        :animate="{ y: 0, scale: 1 }"
        :exit="{ y: -100, scale: 0 }">
        <NAlert class="!rounded-3xl drop-shadow-sm" type="info">
            <div class="leading-5">
                Настройте параметры отправляемой медицинской организации
            </div>
        </NAlert>
    </Motion>
</template>

<style scoped>
:deep(.n-card__action) {
    @apply rounded-b-3xl py-4 px-6;
}
.fade-enter-active,
.fade-leave-active {
    @apply transition-opacity;
}

.fade-enter-from,
.fade-leave-to {
    @apply opacity-0
}
</style>
