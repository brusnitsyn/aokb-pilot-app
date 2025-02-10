<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { IconCirclePlus, IconTableDashed, IconDoorExit } from '@tabler/icons-vue'
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    diagnosis: Array,
    organizationResponses: Array,
    organizationQuestions: Array
})

const showMoParameters = inject('showMoParameters')

const page = usePage()
const hasSelectDepartment = computed(() => page.props.activeDepartment)
const hasShowPrepareModal = ref(false)
const diagnosisId = ref(null)
const isOpenDrawer = ref(false)
const hasOpenDrawer = computed(() => {
    if ((hasSelectDepartment.value && props.organizationResponses === null) || isOpenDrawer.value === true) return true
    else return false
})
const responses = ref(props.organizationResponses ?? {})

// Фильтрация видимых вопросов для МО
const visibleDepartmentQuestions = computed(() => {
    return props.organizationQuestions.filter(question => {
        if (!question.depends_on_answer_id) return true; // Если вопрос не зависит от ответа
        return Object.values(responses.value).includes(question.depends_on_answer_id) !== false;
    });
});

// Проверка, заполнены ли все вопросы
const isAnsweredQuestions = computed(() => {
    for (const questionId in responses.value) {
        const answer = responses.value[questionId]
        const question = props.organizationQuestions.find(itm => itm.id === Number(questionId))

        // Проверка, если вопрос не обязателен
        if (question.requires === false) {
            continue
        }

        // Проверка, если ответ — это массив
        if (Array.isArray(answer)) {
            if (answer.length === 0) {
                return false; // Массив пуст, ответ не заполнен
            }
        }
        // Проверка, если ответ — это одиночное значение
        else if (answer === null || answer === undefined) {
            return false; // Ответ не заполнен
        }
    }

    const requireQuestions = visibleDepartmentQuestions.value.filter(itm => itm.requires === true)

    return requireQuestions.every(item => responses.value.hasOwnProperty(item.id))//requireQuestions.length === Object.keys(responses.value).includes(); // Все ответы заполнены
});

function onSubmit() {
    router.get(route('request.create'), {
        diagnosis_id: diagnosisId.value
    })
}

async function onSubmitDrawer() {
    await axios.post(route('workspace.post'), {
        organization_responses: responses.value
    }).then(() => {
        isOpenDrawer.value = false
        router.reload()
    }).finally(() => {
        //
    })
}

function onShowPrepareModal() {
    if (hasSelectDepartment.value)
      hasShowPrepareModal.value = true
}

const handlePositiveClick = (question, answer) => {
    if (question.type === 'single') {
        responses.value[question.id] = answer.id
    } else {
        if (!responses.value[question.id]) {
            responses.value[question.id] = []
        }
        if (responses.value[question.id].includes(answer.id) === false)
            responses.value[question.id].push(answer.id)
        else {
            const index = responses.value[question.id].indexOf(answer.id);
            if (index !== -1) {
                responses.value[question.id].splice(index, 1);
            }
        }
    }
}
const handleNegativeClick = (question, answer) => {

}

// Отслеживаем изменения ответов на вопросы
watch(responses.value, (newResponses) => {
    // Удаляем ответы на зависимые вопросы, если изменился ответ на предыдущий вопрос
    props.organizationQuestions.forEach((question) => {
        if (question.depends_on_answer_id && Object.values(newResponses).includes(question.depends_on_answer_id) === false) {
            delete responses.value[question.id];
        }
    });
}, { deep: true });
</script>

<template>
    <AppLayout title="Dashboard" @show-mo-parameters="isOpenDrawer = true">
        <NFlex align="center" class="max-w-xl mx-auto h-full">
            <NGrid cols="1 s:2" x-gap="16" y-gap="16" responsive="screen">
                <NGi span="2">
                    <WorkspaceItem header="Выбранный диагноз"
                                   image-url="/assets/svg/illustrations/diagnosis.svg">
                        <template #header>
                            <NSpace vertical :size="3">
                                <div class="max-w-[240px] text-base leading-6 select-none">
                                    Выбранный диагноз: <b>I20.0</b>
                                </div>
                                <div class="max-w-[420px] leading-4 select-none">
                                    Нестабильная стенокардия (впервые возникшая, прогрессирующая)
                                </div>
                            </NSpace>
                        </template>
                    </WorkspaceItem>
                </NGi>
                <NGi>
                    <WorkspaceItem header="Создать запрос на транспортировку"
                                   :disabled="hasSelectDepartment === null || organizationResponses === null"
                                   disabled-reason="Выберите МО и настройте параметры"
                                   image-url="/assets/svg/illustrations/request.svg"
                                   @click="onShowPrepareModal"
                    />
                </NGi>
                <NGi>
                    <WorkspaceItem header="Мои запросы"
                                   image-url="/assets/svg/illustrations/my-requests.svg"
                    />
                </NGi>
                <NGi span="2">
                    <WorkspaceItem header="Выход"
                                   image-url="/assets/svg/illustrations/exit.svg"
                                   type="error"
                    />
                </NGi>
            </NGrid>
        </NFlex>
        <NModal :mask-closable="false"
                display-directive="if"
                v-model:show="hasShowPrepareModal"
                preset="card"
                class="max-w-screen-md"
                title="Подготовка к запросу">
            <NForm @submit.prevent="onSubmit">
                <NFormItem label="Выберите диагноз">
                    <NSelect
                        v-model:value="diagnosisId"
                        :options="diagnosis"
                        label-field="name"
                        value-field="id"
                    />
                </NFormItem>
                <NButton type="primary" attr-type="submit" :disabled="diagnosisId === null">
                    Создать запрос
                </NButton>
            </NForm>
        </NModal>
        <NDrawer :show="hasOpenDrawer" :width="480">
            <NDrawerContent title="Настройка параметров МО">
                <NForm class="flex flex-col gap-y-3">
                    <NFormItem
                        v-for="question in visibleDepartmentQuestions"
                        :key="question.id"
                        :label="question.text"
                        :show-feedback="false"
                        label-style="font-weight: 600;"
                    >
                        <NRadioGroup v-if="question.type === 'single'"
                                     class="flex flex-col gap-y-2"
                                     v-model:value="responses[question.id]">
                            <template v-if="question.requires_confirmation"
                                      v-for="answer in question.answers"
                                      :key="`confirmed-${answer.id}`">
                                <NPopconfirm
                                    @positive-click="handlePositiveClick(question, answer)"
                                    @negative-click="handleNegativeClick(question, answer)"
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
                                v-for="answer in question.answers"
                                :key="answer.id"
                                :value="answer.id"
                                :label="answer.text"
                            />
                        </NRadioGroup>
                        <NCheckboxGroup
                            v-else
                            :value="responses[question.id]"
                            class="flex flex-col gap-y-2"
                        >
                            <template v-if="question.requires_confirmation"
                                      v-for="answer in question.answers"
                                      :key="`confirmed-${answer.id}`">
                                <NPopconfirm
                                    @positive-click="handlePositiveClick(question, answer)"
                                    @negative-click="handleNegativeClick(question, answer)"
                                >
                                    <template #trigger>
                                        <NCheckbox :value="answer.id"
                                                :label="answer.text"
                                        />
                                    </template>
                                    Подтвердите изменение варианта ответа
                                </NPopconfirm>
                            </template>
                            <NCheckbox
                                v-else
                                v-for="answer in question.answers"
                                :key="answer.id"
                                :value="answer.id"
                                :label="answer.text"
                            />
                        </NCheckboxGroup>
                    </NFormItem>
                </NForm>
                <template #footer>
                    <NButton attr-type="submit"
                             type="primary"
                             block
                             :disabled="!isAnsweredQuestions"
                             @click="onSubmitDrawer">
                        Сохранить
                    </NButton>
                </template>
            </NDrawerContent>
        </NDrawer>
    </AppLayout>
</template>
