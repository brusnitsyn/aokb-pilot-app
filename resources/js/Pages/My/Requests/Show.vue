<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconDots, IconExternalLink, IconHome, IconInfoCircle, IconMapPin, IconTag, IconTrash} from "@tabler/icons-vue";
import {Link, router} from "@inertiajs/vue3";
import {NButton, NDropdown, NFlex, NIcon, NTag, NPopover, NTime, NEllipsis, NTooltip, NCountdown} from "naive-ui";
import {useNow} from "@vueuse/core";
import {renderIcon} from "@/Utils/helper.js";
const props = defineProps({
    patients: Array
})

const rowOptions = [
    {
        label: 'Перейти к результатам',
        key: 'result',
        icon: renderIcon(IconExternalLink),
        onClick: (row) => {
            router.visit(route('request.result', { patient_id: row.patient_id }))
        }
    },
    {
        label: 'Изменить статус',
        key: 'change-status',
        icon: renderIcon(IconTag),
        onClick: (row) => {
            window.$message.info('Пока не реализовано')
        }
    },
    {
        type: 'divider'
    },
    {
        label: 'Удалить',
        key: 'delete',
        icon: renderIcon(IconTrash),
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

const now = useNow()
const columns = [
    {
        title: '№ запроса',
        key: 'patient.number',
        width: 100
    },
    {
        title: 'Дата поступления запроса',
        key: 'created_at',
        width: 180,
        render(row) {
            return h(
                NTime,
                {
                    time: row.created_at,
                    format: 'dd.MM.yyyy HH:mm:ss'
                }
            )
        }
    },
    {
        title: 'ФИО',
        key: 'patient.full_name',
        ellipsis: {
            tooltip: true
        },
        width: 208,
    },
    {
        title: 'Дата рождения',
        key: 'patient.date_birth',
        width: 128,
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
        width: 86,
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
        width: 204,
        render(row) {
            return h(
                NFlex,
                {
                    align: 'center',
                    size: 6
                },
                {
                    default: () => [
                        h(
                            NEllipsis,
                            {
                                class: '!max-w-[180px]'
                            },
                            {
                                default: () => row.sender_department.name
                            }
                        ),
                        row.from_department.id === 30 ? h(
                            NTooltip,
                            {
                                trigger: 'hover',
                                style: 'max-width: 300px'
                            },
                            {
                                trigger: () => h(
                                    NIcon,
                                    {
                                        class: 'text-gray-600 text-lg',
                                        component: IconMapPin
                                    },
                                ),
                                header: () => h(
                                    'div',
                                    {},
                                    `${row.from_department.name}`
                                ),
                                default: () => h(
                                    'div',
                                    {
                                        class: 'wrap'
                                    },
                                    row.comment
                                )
                            }
                        ) : null
                    ]
                }
            )
        }
    },
    {
        title: 'Запрос в',
        key: 'department.name',
        width: 138,
        render(row) {
            return h(
                NTooltip,
                {
                    trigger: 'hover',
                },
                {
                    trigger: () => row.to_department.shortname,
                    default: () => h(
                        'div',
                        {
                            class: 'wrap'
                        },
                        row.to_department.name
                    )
                }
            )
        }
    },
    {
        title: 'Сценарий',
        key: 'scenario.name',
        width: 400,
        render(row) {
            return h(
                NFlex,
                {
                    size: 6,
                    align: 'center'
                },
                [
                    h(
                        'div',
                        {
                            class: `rounded-full h-[28px] w-[28px] flex items-center justify-center bg-${row.scenario.color}-200 text-${row.scenario.color}-500`
                        },
                        row.scenario.code
                    ),
                    h(
                        'div',
                        {
                            // class: `text-${row.scenario.color}-500`
                        },
                        row.scenario.name
                    )
                ]
            )
        }
    },
    {
        title: 'Результат',
        width: 94,
        key: 'total_score'
    },
    {
        title: 'Время эвакуации',
        key: 'countdown',
        width: 120,
        render(row) {
            if (row.status_id === 1) return null
            const targetDate = new Date(row.updated_at)
            const endTimestamp = targetDate.getTime() + 24 * 60 * 60 * 1000
            const timeLeft = endTimestamp - now.value.getTime()
            return h(
                NCountdown,
                {
                    duration: Math.abs(timeLeft),
                    render: ({ hours, minutes, seconds }) => {
                        const isExpired = timeLeft <= 0
                        const sign = isExpired ? '-' : ''
                        const timeText = `${sign}${String(Math.abs(hours)).padStart(2, '0')}:${String(Math.abs(minutes)).padStart(2, '0')}:${String(Math.abs(seconds)).padStart(2, '0')}`

                        return h('span', {
                            style: {
                                color: isExpired ? '#d03050' : '#18a058',
                                fontWeight: 'semibold'
                            }
                        }, timeText)
                    }
                },
            )
        }
    },
    {
        title: 'Статус',
        key: 'status.name',
        width: 118,
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

const shouldShowTimerColumn = computed(() => props.patients.some(item => item.status_id !== 1))
</script>

<template>
    <AppLayout title="Запросы МО">
        <NFlex align="center" class="w-full">
            <NFlex align="center" justify="space-between" class="w-full">
                <Link :href="route('workspace')" class="h-full">
                    <NButton secondary round>
                        <template #icon>
                            <NIcon :component="IconHome" />
                        </template>
                        Вернуться на рабочую область
                    </NButton>
                </Link>
            </NFlex>
            <NDataTable :columns="shouldShowTimerColumn ? columns : columns.filter(c => c.key !== 'countdown')" :data="patients" />
        </NFlex>
    </AppLayout>
</template>

<style>
/* Принудительно переопределяем стиль строки */
.expired-row {
    --n-merged-td-color: rgba(255, 0, 0, 0.2) !important;
}
</style>
