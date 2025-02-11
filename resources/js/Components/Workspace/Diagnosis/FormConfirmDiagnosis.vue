<script setup>
import {router, usePage} from "@inertiajs/vue3";

const page = usePage()
const diagnosisId = ref(page.props.selectedDiagnosis ? page.props.selectedDiagnosis.id : null)
const diagnosis = computed({
    get() {
        const diagnosisGroup = page.props.selectedDiagnosisGroup
        if (diagnosisGroup === null) return []
        return diagnosisGroup.diagnoses.map(function (item) {
            return {
                ...item,
                name: `${item.code} ${item.name}`
            }
        })
    }
})

function onSubmit() {
    router.get(route('request.create'), {
        diagnosis_id: diagnosisId.value
    })
}
</script>

<template>
    <NForm @submit.prevent="onSubmit">
        <NFormItem label="Выбранный диагноз">
            <NSelect
                v-model:value="diagnosisId"
                :options="diagnosis"
                label-field="name"
                value-field="id"
            />
        </NFormItem>
        <NButton type="primary" attr-type="submit" round :disabled="diagnosisId === null">
            Создать запрос
        </NButton>
    </NForm>
</template>

<style scoped>

</style>
