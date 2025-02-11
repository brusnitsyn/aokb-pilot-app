<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
defineProps({
    patients: Array
})
const columns = [
    {
        title: 'ФИО',
        key: 'full_name'
    },
    {
        title: 'Диагноз',
        key: 'patient.diagnosis.code'
    },
    {
        title: 'Результат',
        key: 'total_score'
    }
]
</script>

<template>
    <AppLayout>
        <NFlex align="center" class="max-w-5xl mx-auto h-full">
            <NDataTable :columns="columns" :data="patients" />
        </NFlex>
    </AppLayout>
</template>

<style scoped>

</style>
