<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconDots, IconHome, IconInfoCircle} from "@tabler/icons-vue";
import {Link, router} from "@inertiajs/vue3";
import {NButton, NDropdown, NFlex, NIcon, NTag, NPopover, NTime, NEllipsis, NTooltip} from "naive-ui";
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
                title: `Удалить запрос №${row.patient.number}?`,
                content: 'Это действие необратимо. Вы уверены?',
                positiveText: 'Удалить',
                negativeText: 'Нет',
                positiveButtonProps: {
                    round: true,
                    type: 'primary',
                    secondary: true
                },
                negativeButtonProps: {
                    round: true,
                },
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
        title: '№ запроса',
        key: 'patient.number'
    },
    {
        title: 'Дата поступления запроса',
        key: 'created_at',
        render(row) {
            return h(
                NTime,
                {
                    time: row.created_at,
                    format: 'dd.MM.yyyy hh:mm:ss'
                }
            )
        }
    },
    {
        title: 'ФИО',
        key: 'patient.full_name'
    },
    {
        title: 'Дата рождения',
        key: 'patient.date_birth',
        render(row) {
            return h(
                NTime,
                {
                    time: row.patient.date_birth,
                    format: 'dd.MM.yyyy'
                }
            )
        }
    },
    {
        title: 'Диагноз',
        key: 'patient.diagnosis.code',
        render(row) {
            return h(
                NFlex,
                {
                    align: 'center',
                    size: 'small'
                },
                [
                    h(
                        'div',
                        {},
                        {
                            default: () => row.patient.diagnosis.code
                        }
                    ),
                    h(
                        NTooltip,
                        {
                            trigger: 'hover'
                        },
                        {
                            trigger: () => h(
                                NIcon,
                                {
                                    class: 'text-gray-300 text-lg',
                                    component: IconInfoCircle
                                },
                            ),
                            default: () => row.patient.diagnosis.name
                        }
                    )
                ]
            )
        }
    },
    {
        title: 'Запрос из',
        key: 'department.name',
        render(row) {
            return h(
                NEllipsis,
                {
                    class: '!max-w-[160px]'
                },
                {
                    default: () => row.from_department.name
                }
            )
        }
    },
    {
        title: 'Запрос в',
        key: 'department.name',
        render(row) {
            return h(
                NEllipsis,
                {
                    class: '!max-w-[160px]'
                },
                {
                    default: () => row.to_department.name
                }
            )
        }
    },
    {
        title: 'Сценарий',
        key: 'scenario.name'
    },
    {
        title: 'Результат',
        key: 'total_score'
    },
    {
        title: 'Статус',
        key: 'status.name',
        render(row) {
            return h(
                NTag,
                {
                    round: true,
                    type: row.status.type ? row.status.type : 'default'
                },
                {
                    default: () => row.status.name
                }
            )
        }
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
    <AppLayout title="Запросы МО">
        <NFlex align="center" class="w-full">
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
