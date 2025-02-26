<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconCheck,IconListDetails} from "@tabler/icons-vue";
import {NButton, NIcon} from "naive-ui";
import {Link, router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    totalScore: [String, Number],
    patientResult: Object,
    departmentQuestions: Array
})

const page = usePage()
const responsesAnswers = ref([])
onMounted(() => {
    if (props.patientResult.status_id === 1) {
        window.$notifyHelper.info({
            title: 'Статус запроса',
            content: 'Ваш запрос создан, но не отправлен.\nПроверьте результат и подтвердите запрос.'
        })
    }
})

for (const departmentResponse in props.patientResult.department_responses) {
    const model = {
        question: null,
        answer: null
    }

    const answers = props.patientResult.department_responses[departmentResponse]
    if (Array.isArray(answers)) {
        const answerModel = []
        for (const answer in answers) {
            answerModel.push(props.departmentQuestions.find(itm => itm.id === Number(departmentResponse)).answers.find(itm => itm.id === Number(answers[answer])))
        }
        model.answer = answerModel
        model.question = props.departmentQuestions.find(itm => itm.id === Number(departmentResponse))
    } else {
        model.question = props.departmentQuestions.find(itm => itm.id === Number(departmentResponse))
        model.answer = props.departmentQuestions.find(itm => itm.id === Number(departmentResponse)).answers.find(itm => itm.id === answers)
    }

    responsesAnswers.value.push(model)
}

const countRequestedAnswer = computed(() => Object.keys(props.patientResult.patient_responses).length)
</script>

<template>
    <AppLayout :title="`Результаты запроса ${patientResult.patient.number}`">
        <NGrid cols="3" x-gap="32">
            <NGi />
            <NGi>
                <NFlex align="center">
                    <NButtonGroup class="w-full">
                        <NButton @click="router.visit(route('my.request'))"
                                 secondary
                                 round
                                 size="large"
                                 block>
                            <template #icon>
                                <NIcon :component="IconListDetails" />
                            </template>
                            Перейти к запросам МО
                        </NButton>
                    </NButtonGroup>

                    <NAlert class="!rounded-3xl drop-shadow-sm w-full" type="info">
                        <div class="leading-5">
                            Предложенный сценарий &mdash; <span class="font-medium">{{ patientResult.scenario.name }}</span>
                        </div>
                    </NAlert>

                    <NCard class="!rounded-3xl drop-shadow-sm">
                        <template #header>
                            Ранжирование баллов
                        </template>
                        <template #header-extra>
                            <NTag type="error" round class="!px-4">
                                <NSpace align="center" :size="4">
                                    <span class="leading-4">Общее кол-во:</span>
                                    <span class="text-base font-medium">
                                        <NNumberAnimation :from="0.0" :to="totalScore" :precision="1" locale="en-US" :duration="1000" />
                                    </span>
                                </NSpace>
                            </NTag>
                        </template>
                        <NGrid cols="4" x-gap="6" y-gap="6">
                            <NGi>
                                <NTag class="!w-full !h-full flex items-center justify-center !rounded-3xl" type="info" round>
                                    <NSpace vertical align="center" :size="4">
                                        <span>Опрос</span>
                                        <span class="!text-2xl">
                                            <NNumberAnimation :from="0.0" :to="patientResult.patient_score" :precision="1" locale="en-US" :duration="1000" />
                                        </span>
                                    </NSpace>
                                </NTag>
                            </NGi>
                            <NGi>
                                <NTag class="!w-full !h-full flex items-center justify-center !rounded-3xl" type="info" round>
                                    <NSpace vertical align="center" :size="4">
                                        <span>МО</span>
                                        <span class="!text-2xl">
                                            <NNumberAnimation :from="0.0" :to="patientResult.department_score" :precision="1" locale="en-US" :duration="1000" />
                                        </span>
                                    </NSpace>
                                </NTag>
                            </NGi>
                            <NGi>
                                <NTag class="!w-full !h-full flex items-center justify-center !rounded-3xl" type="info" round>
                                    <NSpace vertical align="center" :size="4">
                                        <span>Сценарий</span>
                                        <span class="!text-2xl">
                                            <NNumberAnimation :from="0.0" :to="patientResult.scenario_score" :precision="1" locale="en-US" :duration="1000" />
                                        </span>
                                    </NSpace>
                                </NTag>
                            </NGi>
                            <NGi>
                                <NTag class="!w-full !h-full flex items-center justify-center !rounded-3xl !py-3" type="primary" round>
                                    <NSpace vertical align="center" :size="4">
                                        <span>Код диагноза</span>
                                        <span class="!text-2xl">
                                            {{ patientResult.patient.diagnosis.code }}
                                        </span>
                                    </NSpace>
                                </NTag>
                            </NGi>
                        </NGrid>
                    </NCard>

                    <NButton v-if="patientResult.status_id === 1"
                             @click="router.post(route('my.request.update', { patient_result_id: patientResult.id }))"
                             class="h-12"
                             secondary
                             type="error"
                             size="large"
                             round
                             block>
                        <template #icon>
                            <NIcon :component="IconCheck" />
                        </template>
                        Подтвердить запрос
                    </NButton>

                </NFlex>
            </NGi>
            <NGi class="pt-[48px]">
                <NCard class="!rounded-3xl drop-shadow-sm">
                    <template #header>
                        {{ patientResult.from_department.name }} - параметры
                    </template>
                    <NTabs type="segment" animated>
                        <NTabPane name="responses" tab="Изменяемые параметры">
                            <NList>
                                <NListItem v-for="response in responsesAnswers">
                                    <NSpace vertical :size="2" v-if="Array.isArray(response.answer)">
                                        <div>
                                            {{ response.question.text }}
                                        </div>
                                        <NFlex wrap size="small">
                                            <NTag v-for="answer in response.answer"
                                                  round
                                                  type="primary">
                                                {{ answer.text }}
                                            </NTag>
                                        </NFlex>
                                    </NSpace>
                                    <NFlex v-else justify="space-between" align="center">
                                        <div>
                                            {{ response.question.text }}
                                        </div>
                                        <NTag round
                                              type="primary">
                                            {{ response.answer.text }}
                                        </NTag>
                                    </NFlex>
                                </NListItem>
                            </NList>
                        </NTabPane>
                        <NTabPane name="params" tab="Неизменяемые параметры">
                            <NList v-if="patientResult.from_department.params.length > 0">
                                <NListItem v-for="param in patientResult.from_department.params">
                                    <NFlex justify="space-between" align="center">
                                        <div>
                                            {{ param.param.name }}
                                        </div>
                                        <NTag round
                                              type="primary">
                                            {{ param.param_value.value_name }}
                                        </NTag>
                                    </NFlex>
                                </NListItem>
                            </NList>
                            <NEmpty v-else />
                        </NTabPane>
                    </NTabs>
                </NCard>
            </NGi>
        </NGrid>
    </AppLayout>
</template>

<style scoped>
:deep(.n-list__header) {
    @apply !p-0 !pb-3;
}
:deep(.n-tabs-rail) {
    @apply !rounded-3xl;
}
:deep(.n-tabs-capsule) {
    @apply !rounded-3xl;
}
:deep(.n-statistic-value) {
    @apply text-center;
}
:deep(.n-statistic__label) {
    @apply text-center;
}
</style>
