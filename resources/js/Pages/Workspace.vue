<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";
import FormSelectDiagnosis from "@/Components/Workspace/Diagnosis/FormSelectDiagnosis.vue";
import FormConfirmDiagnosis from "@/Components/Workspace/Diagnosis/FormConfirmDiagnosis.vue";

const props = defineProps({
    diagnosis: Array,
    diagnosisGroups: Array,
    organizationResponses: Array,
    organizationQuestions: Array,
    selectedDiagnosisGroup: Object,
    selectedDiagnosis: Object
})

const showMoParameters = inject('showMoParameters')

const page = usePage()
const hasShowPrepareModal = ref(false)
const hasShowDiagnosesModal = ref(false)
const diagnosisId = ref(null)

const isOpenDrawer = ref(false)

// Проверка, выбраны ли группа и диагноз
const hasSelectedDiagnosis = computed(() => {
    return props.selectedDiagnosisGroup !== null && props.selectedDiagnosis !== null
})

// Проверка, выбрано ли МО
const hasSelectDepartment = computed(() => {
    return page.props.activeDepartment !== null
})

// Проверка, заполнены ли параметры МО
const hasFilledDepartmentResponses = computed(() => {
    return props.organizationResponses !== null
})

const disabledMessage = computed(() => {
    if (hasSelectedDiagnosis.value === false) return 'Установите диагноз'
    if (hasSelectDepartment.value === false || hasFilledDepartmentResponses.value === false) return 'Выберите МО и настройте параметры'
})

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
    if (hasSelectedDiagnosis.value && (hasSelectDepartment.value && hasFilledDepartmentResponses.value))
      hasShowPrepareModal.value = true
}

function onShowDiagnosesModal() {
    hasShowDiagnosesModal.value = true
}
const handleClick = (question, answer) => {
    handlePositiveClick(question, answer)
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
    if (question.type === 'single') {
        responses.value[question.id] = null
    } else {
        if (!responses.value[question.id]) {
            responses.value[question.id] = []
        }
    }
}

// Отслеживаем изменения ответов на вопросы
// watch(responses.value, (newResponses) => {
//     // Удаляем ответы на зависимые вопросы, если изменился ответ на предыдущий вопрос
//     props.organizationQuestions.forEach((question) => {
//         if (question.depends_on_answer_id && Object.values(newResponses).includes(question.depends_on_answer_id) === false) {
//             delete responses.value[question.id];
//         }
//     });
// }, { deep: true });
</script>

<template>
    <AppLayout title="Рабочая область" @show-mo-parameters="isOpenDrawer = true">
        <NFlex align="center" class="max-w-xl mx-auto h-full">
            <NGrid cols="1" x-gap="16" y-gap="16" responsive="screen">
                <NGi>
                    <WorkspaceItem header="Создать запрос на транспортировку"
                                   image-url="/assets/svg/illustrations/request.svg"
                                   :href="route('request.create')"
                    />
                </NGi>
                <NGi>
                    <WorkspaceItem header="Запросы МО"
                                   image-url="/assets/svg/illustrations/my-requests.svg"
                                   :href="route('my.request')"
                    />
                </NGi>
                <NGi>
                    <WorkspaceItem header="Выход"
                                   image-url="/assets/svg/illustrations/exit.svg"
                                   type="error"
                    />
                </NGi>
            </NGrid>
        </NFlex>
    </AppLayout>
</template>
