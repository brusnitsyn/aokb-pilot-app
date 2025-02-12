<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconHome} from "@tabler/icons-vue";
import {Link} from "@inertiajs/vue3";
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
        <NFlex align="center" class="max-w-5xl mx-auto">
            <Link :href="route('workspace')" class="h-full">
                <NButton secondary round>
                    <template #icon>
                        <NIcon :component="IconHome" />
                    </template>
                    Вернуться на рабочую область
                </NButton>
            </Link>
            <NDataTable :columns="columns" :data="patients" />
        </NFlex>
    </AppLayout>
</template>

<style scoped>

</style>
