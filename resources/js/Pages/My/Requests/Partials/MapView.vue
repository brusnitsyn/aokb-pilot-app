<script setup>
import {IconDots, IconLock} from "@tabler/icons-vue";
import {renderTime} from "@/Utils/helper.js";
import {
    YandexMap, YandexMapClusterer, YandexMapControls,
    YandexMapDefaultFeaturesLayer,
    YandexMapDefaultSchemeLayer,
    YandexMapEntity, YandexMapGeolocationControl,
    YandexMapMarker
} from "vue-yandex-maps";
import {NButton, NDropdown, NFlex, NIcon, NTag, NTime} from "naive-ui";
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    extendedMenuOptions: Array
})
const results = defineModel('results')
const pageProps = usePage().props

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
const hasShowRequest = ref(false)
const showedRequest = ref(null)

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
</script>

<template>
    <NGrid cols="8" x-gap="16" class="min-h-[calc(100vh-226px)] max-h-[calc(100vh-226px)] overflow-hidden">
        <NGi span="2">
            <div class="bg-white rounded-3xl border shadow-sm min-h-[calc(100vh-226px)] max-h-[calc(100vh-226px)] overflow-hidden">
                <NTabs type="segment" animated>
                    <NTabPane name="my-requests" tab="Запросы из МО">
                        <NSpace vertical>
                            <NFlex class="pt-2 pb-2 px-4">
                                <NInput round size="large" />
                            </NFlex>
                            <NScrollbar class="min-h-[calc(100vh-360px)] max-h-[calc(100vh-360px)]">
                                <NList hoverable>
                                    <NListItem v-for="patientResult in results"
                                               class="relative"
                                               @click="onShowRequest(patientResult)">
                                        <div v-show="patientResult.isActive"
                                             class="absolute left-0 inset-y-0 w-1 bg-orange-500 rounded-r-3xl" />
                                        <NSpace vertical>
                                            <NSpace vertical :size="2">
                                                <NFlex align="center" justify="space-between">
                                                    <NSpace align="center">
                                                        <NTag v-if="patientResult.isNew" round size="small" type="info">
                                                            НОВЫЙ
                                                        </NTag>
                                                        <NTag round size="small" :type="patientResult.status.type ?? 'default'">
                                                            {{ patientResult.status.name }}
                                                        </NTag>
                                                        <span class="font-medium">
                                                            Запрос №{{ patientResult.patient.number }}
                                                        </span>
                                                    </NSpace>
                                                    <NDropdown placement="bottom-end"
                                                               trigger="click"
                                                               :options="extendedMenuOptions" @select="(key, option) => option.onClick(patientResult)">
                                                        <NButton text class="text-xl">
                                                            <NIcon :component="IconDots" />
                                                        </NButton>
                                                    </NDropdown>
                                                </NFlex>
                                            </NSpace>
                                        </NSpace>
                                    </NListItem>
                                </NList>
                            </NScrollbar>
                        </NSpace>
                    </NTabPane>
                    <NTabPane :disabled="!pageProps.auth.user.department.is_received" name="sender-requests">
                        <template #tab>
                            <NFlex align="center" size="small">
                                Запросы в МО
                                <NPopover v-if="!pageProps.auth.user.department.is_received">
                                    <template #trigger>
                                        <div class="flex items-center justify-center cursor-help">
                                            <NIcon :component="IconLock" class="text-base" />
                                        </div>
                                    </template>
                                    Ваша МО не является принимающей
                                </NPopover>
                            </NFlex>
                        </template>
                        <NSpace vertical>
                            <NFlex class="pt-2 pb-2 px-4">
                                <NInput round size="large" />
                            </NFlex>
                            <NScrollbar class="min-h-[calc(100vh-298px)] max-h-[calc(100vh-298px)]">
                                <NList hoverable>
                                    <NListItem v-for="patientResult in results"
                                               class="relative"
                                               @click="onShowRequest(patientResult)">
                                        <div v-show="patientResult.isActive"
                                             class="absolute left-0 inset-y-0 w-1 bg-orange-500 rounded-r-3xl" />
                                        <NSpace vertical>
                                            <NSpace vertical :size="2">
                                                <NFlex align="center" justify="space-between">
                                                    <NSpace align="center">
                                                        <NTag v-if="patientResult.isNew" round size="small" type="info">
                                                            НОВЫЙ
                                                        </NTag>
                                                        <NTag round size="small" :type="patientResult.status.type ?? 'default'">
                                                            {{ patientResult.status.name }}
                                                        </NTag>
                                                        <span class="font-medium">
                                                    Запрос №{{ patientResult.patient.number }}
                                                </span>
                                                    </NSpace>
                                                    <NDropdown placement="bottom-end"
                                                               trigger="click"
                                                               :options="extendedMenuOptions" @select="(key, option) => option.onClick(patientResult)">
                                                        <NButton text class="text-xl">
                                                            <NIcon :component="IconDots" />
                                                        </NButton>
                                                    </NDropdown>
                                                </NFlex>
                                            </NSpace>
                                        </NSpace>
                                    </NListItem>
                                </NList>
                            </NScrollbar>
                        </NSpace>
                    </NTabPane>
                </NTabs>
            </div>

        </NGi>
        <NGi span="6">
            <NSkeleton v-show="map === null" height="100%" width="100%" class="rounded-3xl overflow-clip border shadow-sm" />
            <YandexMap v-show="map !== null"
                       v-model="map"
                       cursor-grab
                       :settings="mapSettings"
                       height="100%"
                       class="rounded-3xl overflow-clip border shadow-sm">
                <!--                        <YandexMapDefaultSatelliteLayer v-if="hasShowSatelliteLayer" />-->
                <YandexMapDefaultSchemeLayer />
                <YandexMapDefaultFeaturesLayer />

                <YandexMapClusterer zoom-on-cluster-click
                                    :grid-size="200">
                    <YandexMapMarker v-for="patient in results"
                                     :settings="{coordinates: patient.coords, hideOutsideViewport: true}">
                        <div class="center-marker"></div>
                    </YandexMapMarker>
                    <template #cluster="{ length }">
                        <div class="cluster">
                            {{ length }}
                        </div>
                    </template>
                </YandexMapClusterer>

                <YandexMapControls v-if="hasShowRequest" :settings="{ position: 'top left' }">
                    <YandexMapEntity>
                        <div class="bg-white rounded-3xl overflow-clip border shadow-sm w-[480px] px-4 py-3">
                            <NSpace vertical>
                                <span class="font-medium">Информация о запросе №{{ showedRequest.patient.number }}</span>
                                <NGrid cols="8" y-gap="4">
                                    <NGi span="3">
                                        ФИО
                                    </NGi>
                                    <NGi span="5">
                                        <span class="font-medium">{{ showedRequest.patient.full_name }}</span>
                                    </NGi>
                                    <NGi span="3">
                                        Дата рождения
                                    </NGi>
                                    <NGi span="5">
                                        <NTime class="font-medium" :time="showedRequest.patient.date_birth" format="d.MM.Y" />
                                    </NGi>
                                    <NGi span="3">
                                        Диагноз
                                    </NGi>
                                    <NGi span="5">
                                        <span class="font-medium">{{ showedRequest.patient.diagnosis.code }}</span>
                                    </NGi>
                                    <NGi span="3">
                                        Время эвакуации
                                    </NGi>
                                    <NGi span="5">
                                        <component :is="renderTime(showedRequest.status_id, showedRequest.status_changed_at, showedRequest.last_status_at)" />
                                    </NGi>
                                </NGrid>
                            </NSpace>
                        </div>
                    </YandexMapEntity>
                </YandexMapControls>

                <YandexMapControls :settings="{ position: 'bottom left' }">
                    <YandexMapGeolocationControl />
                </YandexMapControls>

            </YandexMap>
        </NGi>
    </NGrid>
</template>

<style scoped>
:deep(.n-tabs-rail) {
    @apply !rounded-3xl;
}
:deep(.n-tabs-capsule) {
    @apply !rounded-3xl;
}
</style>
