<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconDots, IconHome} from "@tabler/icons-vue";
import {Link, router} from "@inertiajs/vue3";
import {NButton, NDropdown, NFlex, NIcon} from "naive-ui";
defineProps({
    patients: Array
})

const rowOptions = [
    {
        label: 'Перейти к результатам',
        key: 'result',
        onClick: (row) => {
            router.visit(route('request.result', { patient_id: row.patient_id }))
        }
    },
    {
        type: 'divider'
    },
    {
        label: 'Удалить',
        key: 'delete',
        onClick: (row) => {
            window.$dialog.warning({
                title: 'Подтвердите действие',
                content: 'Это действие необратимо. Вы уверены?',
                positiveText: 'Да',
                negativeText: 'Нет',
                draggable: true,
                onPositiveClick: () => {
                    router.delete(route('request.delete', {
                        patient_result_id: row.id
                    }))
                },
                onNegativeClick: () => {

                }
            })
        }
    }
]

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
    },
    {
        key: 'actions',
        render(row) {
            return h(
                NFlex,
                {

                },
                {
                    default: () => h(
                        NDropdown,
                        {
                            trigger: 'click',
                            placement: 'bottom-end',
                            options: rowOptions,
                            onSelect: (key, option) => option.onClick(row)
                        },
                        {
                            default: () => h(NButton, { text: true, class: 'text-xl' }, { default: () => h(NIcon, null, { default: () => h(IconDots) }) })
                        }
                    )
                }
            )
        }
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
