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

    return visibleDepartmentQuestions.value.length === Object.keys(responses.value).length; // Все ответы заполнены
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
        <NFlex vertical :size="20" class="max-w-xl mx-auto h-full" justify="space-between">
            <WorkspaceItem header="Создать запрос на транспортировку"
                           :icon="IconCirclePlus"
                           :disabled="hasSelectDepartment === null || organizationResponses === null"
                           disabled-reason="Выберите МО и настройте параметры"
                           @click="onShowPrepareModal"
            />
            <WorkspaceItem header="Мои запросы"
                           :icon="IconTableDashed"
            />
            <WorkspaceItem header="Выход"
                           :icon="IconDoorExit"
                           type="error"
            />
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
                            <NRadio
                                v-for="answer in question.answers"
                                :key="answer.id"
                                :value="answer.id"
                                :label="answer.text"
                            />
                        </NRadioGroup>
                        <NCheckboxGroup
                            v-else
                            v-model:value="responses[question.id]"
                            class="flex flex-col gap-y-2"
                        >
                            <NCheckbox
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
