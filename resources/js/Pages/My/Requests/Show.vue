<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {
    IconDots,
    IconExternalLink,
    IconHome,
    IconInfoCircle,
    IconMap, IconMap2,
    IconMapPin, IconTable,
    IconTag,
    IconTrash
} from "@tabler/icons-vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {NButton, NDropdown, NFlex, NIcon, NTag, NPopover, NTime, NEllipsis, NTooltip, NCountdown} from "naive-ui";
import {useNow} from "@vueuse/core";
import {fetchUserLocation, renderIcon} from "@/Utils/helper.js";
import {Motion} from 'motion-v'
import {
    YandexMap,
    YandexMapClusterer, YandexMapControls,
    YandexMapDefaultFeaturesLayer,
    YandexMapDefaultSchemeLayer, YandexMapGeolocationControl,
    YandexMapMarker
} from "vue-yandex-maps";
import ChangeRequestStatusModal from "@/Components/Request/ChangeRequestStatusModal.vue";
const props = defineProps({
    patients: Array
})

const currentPatientResult = ref()
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
        label: 'Посмотреть на карте',
        key: 'show-map',
        icon: renderIcon(IconMap),
        onClick: (row) => {
            router.visit(route('patientResult.show', { patientResult: row.id }))
        }
    },
    {
        label: 'Изменить статус',
        key: 'change-status',
        icon: renderIcon(IconTag),
        onClick: (row) => {
            currentPatientResult.value = row
            hasShowChangeRequestStatusModal.value = true
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
const hasShowChangeRequestStatusModal = ref(false)
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
                                default: () => row.from_department.id !== 30 ? h(
                                    Link,
                                    {
                                        href: route('patientResult.show', { patientResult: row.id })
                                    },
                                    {
                                        default: () => row.sender_department.name
                                    }
                                ) : row.sender_department.name
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
                                    Link,
                                    {
                                        href: route('patientResult.show', { patientResult: row.id })
                                    },
                                    {
                                        default: () => h(
                                            NIcon,
                                            {
                                                class: 'text-gray-600 text-lg',
                                                component: IconMapPin
                                            },
                                        )
                                    }
                                ),
                                header: () => h(
                                    'div',
                                    {},
                                    `${row.sender_department.coords[1]?.toFixed(2)}, ${row.sender_department.coords[0]?.toFixed(2)}`
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
            if (row.status_id === 1) return h(
                'span',
                {
                    style: {
                        fontWeight: 500
                    }
                },
                {
                    default: () => '----'
                }
            )

            const targetDate = new Date(row.updated_at) // Берём дату из строки
            const endTimestamp = targetDate.getTime() + 24 * 60 * 60 * 1000
            const timeLeft = endTimestamp - now.value
            const isExpired = timeLeft <= 0
            const hoursLeft = timeLeft / (1000 * 60 * 60)

            if (hoursLeft <= -48) {
                return h(Motion, {
                    initial: { opacity: 1 },
                    animate: {
                        opacity: [0.2, 1],
                        transition: {
                            duration: 1.0,
                            repeat: Infinity,
                            repeatType: 'mirror',
                            ease: 'easeInOut'
                        }
                    }
                }, () => h('span', {
                    style: {
                        color: '#d03050',
                        fontWeight: 500
                    }
                }, '-48:00:00'))
            }

            return h('span', {
                style: {
                    color: isExpired ? '#d03050' : '#18a058',
                    fontWeight: 500
                }
            }, formatTime(timeLeft))
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

const map = shallowRef(null)
const mapSettings = ref({
    location: {
        center: fetchUserLocation(),
        zoom: 10
    },
    zoomStrategy: 'zoomToCenter',
    behaviors: [
        'dblClick',
        'drag',
        'scrollZoom',
        'mouseRotate',
        'mouseTilt',
        'magnifier',
        'oneFingerZoom',
        'panTilt',
        'pinchRotate',
        'pinchZoom'
    ],
})
const hasShowMapPreview = ref(false)

const formatTime = (ms) => {
    const absMs = Math.abs(ms)
    const hours = Math.floor(absMs / (1000 * 60 * 60))
    const minutes = Math.floor((absMs % (1000 * 60 * 60)) / (1000 * 60))
    const seconds = Math.floor((absMs % (1000 * 60)) / 1000)

    const sign = ms <= 0 ? '-' : ''
    return `${sign}${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
}

const shouldShowTimerColumn = computed(() => props.patients.some(item => item.status_id !== 1))
</script>

<template>
    <AppLayout title="Запросы МО">
        <NFlex vertical align="start" justify="start" class="w-full h-full">
            <NFlex align="center" justify="space-between" class="w-full">
                <Link :href="route('workspace')" class="h-full">
                    <NButton secondary round>
                        <template #icon>
                            <NIcon :component="IconHome" />
                        </template>
                        Вернуться на рабочую область
                    </NButton>
                </Link>
                <NButton secondary round @click="hasShowMapPreview = !hasShowMapPreview">
                    <template #icon>
                        <NIcon v-if="!hasShowMapPreview" :component="IconMap2" />
                        <NIcon v-else :component="IconTable" />
                    </template>
                    {{ !hasShowMapPreview ? 'Карта' : 'Таблица' }}
                </NButton>
            </NFlex>
            <NDataTable v-if="!hasShowMapPreview" :columns="shouldShowTimerColumn ? columns : columns.filter(c => c.key !== 'countdown')" :data="patients" />
            <YandexMap v-else
                       v-model="map"
                       cursor-grab
                       :settings="mapSettings"
                       height="100%"
                       class="rounded-3xl overflow-clip border shadow-sm">
                <YandexMapDefaultSchemeLayer />
                <YandexMapDefaultFeaturesLayer />

                <YandexMapClusterer zoom-on-cluster-click
                                    :grid-size="200">
                    <YandexMapMarker v-for="patient in patients"
                                     :settings="{coordinates: patient.coords, hideOutsideViewport: true}">
                        <div class="center-marker"></div>
                    </YandexMapMarker>
                    <template #cluster="{ length }">
                        <div class="cluster">
                            {{ length }}
                        </div>
                    </template>
                </YandexMapClusterer>

                <YandexMapControls :settings="{ position: 'left' }">
                    <YandexMapGeolocationControl />
                </YandexMapControls>

            </YandexMap>
        </NFlex>
        <ChangeRequestStatusModal v-model:show="hasShowChangeRequestStatusModal" :patient-result="currentPatientResult" />
    </AppLayout>
</template>

<style>
.center-marker {
    width: 20px;
    height: 20px;
    background-color: #EC6608;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(236, 102, 8, 0.5);
    transform: translate(-50%, -50%);
}

.cluster {
    @apply py-1.5 px-3 text-white font-medium;
    background-color: #EC6608;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(236, 102, 8, 0.5);
    transform: translate(-50%, -50%);
}
</style>
