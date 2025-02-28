<script setup>
import {IconArrowLeft, IconArrowRight} from "@tabler/icons-vue";
import {router, usePage} from "@inertiajs/vue3";
import {Motion} from 'motion-v'
import AppSelect from "@/Components/App/AppSelect.vue";
const props = defineProps(['stage', 'diagnoses', 'validationMessage'])
const { hasPrevStage, onPrevStage, onNextStage } = inject('navigate')
const selectedDiagnosisId = defineModel('selectedDiagnosisId')
const model = ref({
    diagnosis_group_id: null,
    diagnosis_id: null
})
const rules = {
    diagnosis_group_id: {
        type: 'number',
        required: true,
        message: props.validationMessage
    },
    diagnosis_id: {
        type: 'number',
        required: true,
        message: props.validationMessage
    }
}
const page = usePage()
const diagnosisGroupId = ref(null)
const diagnosisId = ref(null)

const progress = computed(() => {
    const filled = Object.values(model.value).filter(item => {
        return item !== null && item !== ''
    }).length
    const total = Object.keys(model.value).length
    const result = (filled / total) * 100
    return Number(result.toLocaleString(undefined, {
        maximumFractionDigits: 0
    }))
})

const diagnosis = computed({
    get() {
        if (model.value.diagnosis_group_id === null) return []
        return page.props.diagnosisGroups.find(item => item.id === model.value.diagnosis_group_id).diagnoses
            .map(function (item) {
                return {
                    ...item,
                    name: `${item.code} ${item.name}`
                }
            })
    }
})

const updateDiagnosisGroup = (value) => {
    if (model.value.diagnosis_group_id !== value) {
        model.value.diagnosis_group_id = value
        model.value.diagnosis_id = null
    }
}

const onSubmit = async () => {
    await axios.post(route('workspace.diagnosis.post'), {
        ...model.value
    }).then(() => {
        router.reload()
        selectedDiagnosisId.value = model.value.diagnosis_id
        onNextStage()
    })
}
</script>

<template>
    <Motion
        v-if="stage === 'diagnosis'"
        key="diagnosis"
        :initial="{ y: 100, opacity: 0 }"
        :animate="{ y: 0, opacity: 1 }"
        :exit="{ y: 100, opacity: 0 }">
        <NCard class="!rounded-3xl drop-shadow-sm">
            <template #header>
                Установка диагноза пациента
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
            <NForm ref="diagnosisPatientRef"
                   :model="model"
                   :rules="rules">
                <NFormItem label="Выберите группу диагнозов" path="diagnosis_group_id">
                    <AppSelect
                        :value="model.diagnosis_group_id"
                        :options="page.props.diagnosisGroups"
                        @update:value="value => updateDiagnosisGroup(value)"
                        label-field="name"
                        value-field="id"
                    />
                </NFormItem>
                <NFormItem v-if="model.diagnosis_group_id !== null" label="Выберите диагноз" path="diagnosis_id">
                    <AppSelect
                        v-model:value="model.diagnosis_id"
                        :options="diagnosis"
                        label-field="name"
                        value-field="id"
                    />
                </NFormItem>
            </NForm>
            <template #action>
                <NButtonGroup class="flex justify-end">
                    <NButton v-if="hasPrevStage"
                             secondary
                             round
                             @click="onPrevStage">
                        <template #icon>
                            <NIcon :component="IconArrowLeft" />
                        </template>
                        Назад
                    </NButton>
                    <NButton
                        type="primary"
                        secondary
                        round
                        :disabled="progress !== 100"
                        @click="onSubmit"
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

<!--    <Motion-->
<!--        v-if="stage === 'diagnosis' && progress !== 100"-->
<!--        :initial="{ y: 100 }"-->
<!--        :animate="{ y: 0, scale: 1 }"-->
<!--        :exit="{ y: -100, scale: 0 }">-->
<!--        <NAlert class="!rounded-3xl drop-shadow-sm" type="info">-->
<!--            <div class="leading-5">-->
<!--                Установите диагноз пациента-->
<!--            </div>-->
<!--        </NAlert>-->
<!--    </Motion>-->
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
