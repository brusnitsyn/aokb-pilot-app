<script setup>
import {
    YandexMap,
    YandexMapDefaultFeaturesLayer,
    YandexMapDefaultMarker,
    YandexMapDefaultSchemeLayer
} from "vue-yandex-maps";
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconInfoCircle} from "@tabler/icons-vue";
import AppBackButton from "@/Components/App/AppBackButton.vue";

const props = defineProps({
    patientResult: Object
})

const map = shallowRef(null)

const requestCoords = computed(() => {
    if (props.patientResult.from_department_id === 30) return props.patientResult.coords

    return props.patientResult.sender_department.coords
})

const mapSettings = {
    location: {
        center: requestCoords.value,
        zoom: 15
    }
}
</script>

<template>
    <AppLayout :title="`Запрос ${patientResult.patient.number}`">
        <NGrid cols="8" class="h-full" x-gap="16">
            <NGi span="2">
                <div class="bg-white rounded-3xl border h-full shadow-sm">
                    <NFlex align="center" justify="space-between" class="px-6 pt-4">
                        <NSpace vertical :size="0">
                            <AppBackButton :parent-route="route('my.request')" />
                            <h1 class="font-medium text-lg">
                                Запрос №{{ patientResult.patient.number }}
                            </h1>
                        </NSpace>
                        <NTag round type="info">
                            Общий балл: {{ patientResult.total_score }}
                        </NTag>
                    </NFlex>
                    <NList hoverable class="mt-3">
                        <NListItem>
                            <NGrid cols="5" class="px-1.5">
                                <NGi span="2">
                                    ФИО
                                </NGi>
                                <NGi span="3" class="font-medium">
                                    {{ patientResult.patient.full_name }}
                                </NGi>
                            </NGrid>
                        </NListItem>
                        <NListItem>
                            <NGrid cols="5" class="px-1.5">
                                <NGi span="2">
                                    Дата рождения
                                </NGi>
                                <NGi span="3" class="font-medium">
                                    <NTime :time="patientResult.patient.date_birth" format="dd.MM.yyyy" />
                                </NGi>
                            </NGrid>
                        </NListItem>
                        <NListItem>
                            <NGrid cols="5" class="px-1.5">
                                <NGi span="2">
                                    Диагноз
                                </NGi>
                                <NGi span="3" class="font-medium">
                                    <NSpace size="small" align="center">
                                        {{ patientResult.patient.diagnosis.code }}
                                        <NPopover>
                                            <template #trigger>
                                                <NFlex align="center" justify="center">
                                                    <NIcon :component="IconInfoCircle" size="18" />
                                                </NFlex>
                                            </template>
                                            {{ patientResult.patient.diagnosis.name }}
                                        </NPopover>
                                    </NSpace>
                                </NGi>
                            </NGrid>
                        </NListItem>
                        <NListItem v-if="patientResult.comment">
                            <NGrid cols="5" class="px-1.5">
                                <NGi span="2">
                                    Комментарий
                                </NGi>
                                <NGi span="3" class="font-medium">
                                    {{ patientResult.comment }}
                                </NGi>
                            </NGrid>
                        </NListItem>
                    </NList>
                </div>
            </NGi>
            <NGi span="6">
                <YandexMap v-model="map"
                           :settings="mapSettings"
                           class="rounded-3xl overflow-clip border shadow-sm">
                    <YandexMapDefaultSchemeLayer />
                    <YandexMapDefaultFeaturesLayer />

                    <YandexMapDefaultMarker :settings="{
                        coordinates: requestCoords
                    }" />
                </YandexMap>
            </NGi>
        </NGrid>
    </AppLayout>
</template>

<style scoped>

</style>
