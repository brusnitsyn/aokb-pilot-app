<script setup>
import {router, usePage} from "@inertiajs/vue3";

const emits = defineEmits(['close'])
const page = usePage()
const diagnosisGroupId = ref(page.props.selectedDiagnosisGroup ? page.props.selectedDiagnosisGroup.id : null)
const diagnosisId = ref(page.props.selectedDiagnosis ? page.props.selectedDiagnosis.id : null)
const diagnosis = computed({
    get() {
        if (diagnosisGroupId.value === null) return []
        return page.props.diagnosisGroups.find(item => item.id === diagnosisGroupId.value).diagnoses
            .map(function (item) {
                return {
                    ...item,
                    name: `${item.code} ${item.name}`
                }
            })
    }
})
const selectDiagnosisGroup = computed({
    get() {
        return diagnosisGroupId.value
    },
    set(value) {
        if (diagnosisGroupId.value !== value) {
            diagnosisGroupId.value = value
            diagnosisId.value = null
        }
    }
})
const onSubmit = async () => {
    await axios.post(route('workspace.diagnosis.post'), {
        diagnosisGroupId: diagnosisGroupId.value,
        diagnosisId: diagnosisId.value,
    }).then(() => {
        emits('close')
        router.visit(route('workspace'), {
            preserveState: false
        })
    })
}
</script>

<template>
    <NForm @submit.prevent="onSubmit">
        <NFormItem label="Выберите группу диагнозов">
            <NSelect
                v-model:value="selectDiagnosisGroup"
                :options="page.props.diagnosisGroups"
                label-field="name"
                value-field="id"
            />
        </NFormItem>
        <NFormItem v-if="diagnosisGroupId !== null" label="Выберите диагноз">
            <NSelect
                v-model:value="diagnosisId"
                :options="diagnosis"
                label-field="name"
                value-field="id"
            />
        </NFormItem>
        <NButton type="primary" attr-type="submit" round :disabled="diagnosisId === null">
            Установить
        </NButton>
    </NForm>
</template>

<style scoped>

</style>
