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
import {useLocalStorage, useNow} from "@vueuse/core";
import {fetchUserLocation, renderIcon, renderTime} from "@/Utils/helper.js";
import {Motion} from 'motion-v'
import {
    YandexMap,
    YandexMapClusterer, YandexMapControls,
    YandexMapDefaultFeaturesLayer, YandexMapDefaultSatelliteLayer,
    YandexMapDefaultSchemeLayer, YandexMapEntity, YandexMapGeolocationControl,
    YandexMapMarker
} from "vue-yandex-maps";
import ChangeRequestStatusModal from "@/Components/Request/ChangeRequestStatusModal.vue";
import AppBackButton from "@/Components/App/AppBackButton.vue";
import MapView from "@/Pages/My/Requests/Partials/MapView.vue";
const props = defineProps({
    patients: Array
})
const pageProps = usePage().props

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
                                class: '!max-w-[160px]'
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
            return h(renderTime(row.status_id, row.status_changed_at, row.last_status_at))
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
        width: 48,
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

const results = ref([...props.patients.map(item => {
    return {
        ...item,
        isNew: false,
        isActive: false
    }
})])
const map = shallowRef()
const mapSettings = ref({
    location: {
        center: [
            127.366460, 52.586606
        ],
        zoom: 7
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
const previewMode = useLocalStorage('previewMode', 'table')
const hasShowRequest = ref(false)
const showedRequest = ref(null)
const hasShowSatelliteLayer = ref(false)
const hasShowMapPreview = computed(() => previewMode.value === 'map')
const shouldShowTimerColumn = computed(() => results.value.some(item => item.status_id !== 1))

const onChangePreviewMode = () => {
    if (previewMode.value === 'table')
        previewMode.value = 'map'
    else
        previewMode.value = 'table'
}

const onShowRequest = (patientResult) => {
    hasShowRequest.value = true
    mapSettings.value.location.center = patientResult.coords
    mapSettings.value.location.zoom = 16
    showedRequest.value = patientResult

    results.value = results.value.map(item => {
        return {
            ...item,
            isActive: false
        }
    })
    const result = results.value.find(item => item.id === patientResult.id)
    result.isActive = true
}

const onResetRequest = () => {
    hasShowRequest.value = false
    mapSettings.value.location.center = [127.366460, 52.586606]
    mapSettings.value.location.zoom = 7
    showedRequest.value = null
}

const onChangeLayer = () => {
    if (hasShowSatelliteLayer.value) {
        hasShowSatelliteLayer.value = false
    } else {
        hasShowSatelliteLayer.value = true
    }
}

onMounted(() => {
    window.Echo.private(`${import.meta.env.VITE_REVERB_APP}.department.${pageProps.auth.user.department.id}`)
        .listen('PatientResultCreated', (data) => {
            const result = {
                ...data,
                isNew: true,
                isActive: false
            }
            results.value.unshift(result)
        })
        .listen('PatientResultUpdated', (data) => {
            const result = {
                ...data,
                isNew: false,
                isActive: false,
                isUpdated: true
            }
            const patientResultIndex = results.value.findIndex((item) => item.id === data.id)
            results.value[patientResultIndex] = result
        })
})
</script>

<template>
    <AppLayout title="Запросы МО">
        <NFlex vertical align="start" justify="start" class="w-full h-full">
            <NFlex align="center" justify="space-between" class="w-full">
                <NSpace align="center">
                    <Link :href="route('workspace')" class="h-full">
                        <NButton secondary round>
                            <template #icon>
                                <NIcon :component="IconHome" />
                            </template>
                            Вернуться на рабочую область
                        </NButton>
                    </Link>
                </NSpace>
                <NButton secondary round @click="onChangePreviewMode">
                    <template #icon>
                        <NIcon v-if="!hasShowMapPreview" :component="IconMap2" />
                        <NIcon v-else :component="IconTable" />
                    </template>
                    {{ !hasShowMapPreview ? 'Карта' : 'Таблица' }}
                </NButton>
            </NFlex>
            <NDataTable v-if="!hasShowMapPreview"
                        max-height="calc(100vh - 298px)"
                        min-height="calc(100vh - 298px)"
                        :columns="shouldShowTimerColumn ? columns : columns.filter(c => c.key !== 'countdown')"
                        :data="results" />
            <MapView v-else v-model:results="results" :extended-menu-options="rowOptions" />
        </NFlex>
        <ChangeRequestStatusModal v-model:show="hasShowChangeRequestStatusModal" :patient-result="currentPatientResult" />
    </AppLayout>
</template>

<style>
.center-marker {
    width: 28px;
    height: 28px;
    background-color: #EC6608;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(236, 102, 8, 0.5);
    transform: translate(-50%, -50%);
}

.cluster {
    @apply w-10 h-10 text-white font-medium flex items-center justify-center;
    background-color: #EC6608;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(236, 102, 8, 0.5);
}
</style>
