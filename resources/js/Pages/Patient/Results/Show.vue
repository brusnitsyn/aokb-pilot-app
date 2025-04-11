<script setup>
import { Motion } from 'motion-v'
import AppLayout from "@/Layouts/AppLayout.vue";
import {IconInfoCircle, IconTag} from "@tabler/icons-vue";
import AppBackButton from "@/Components/App/AppBackButton.vue";
import ChangeRequestStatusModal from "@/Components/Request/ChangeRequestStatusModal.vue";
import AppMap from "@/Components/App/AppMap.vue";
import {formatTime, renderTime} from "../../../Utils/helper.js";

const props = defineProps({
    patientResult: Object
})

const requestCoords = computed(() => {
    if (props.patientResult.from_department_id === 30) return props.patientResult.coords

    return props.patientResult.sender_department.coords
})

const mapSettings = {
    location: {
        center: requestCoords.value,
        zoom: 15
    },
    zoomStrategy: 'zoomToCenter',
}
const markers = [
    {
        settings: {
            coordinates: requestCoords.value
        }
    }
]

const hasShowChangeRequestStatusModal = ref(false)
</script>

<template>
    <AppLayout :title="`Запрос ${patientResult.patient.number}`">
        <NGrid cols="8" class="h-full" x-gap="16">
            <NGi span="2">
                <div class="bg-white rounded-3xl border h-full shadow-sm">
                    <NFlex vertical align="start" justify="space-between" class="w-full h-full">
                        <NSpace vertical class="w-full">
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
                                <NListItem>
                                    <NGrid cols="5" class="px-1.5">
                                        <NGi span="2">
                                            Время эвакуации
                                        </NGi>
                                        <NGi span="3" class="font-medium">
                                            <component :is="renderTime(patientResult.status_id, patientResult.status_changed_at, patientResult.last_status_at)" />
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
                        </NSpace>
                        <NSpace vertical class="px-6 pb-6 w-full">
                            <NButton secondary block @click="hasShowChangeRequestStatusModal = true">
                                <template #icon>
                                    <NIcon :component="IconTag" />
                                </template>
                                Изменить статус
                            </NButton>
                        </NSpace>
                    </NFlex>
                </div>
            </NGi>
            <NGi span="6">
                <AppMap :settings="mapSettings" :markers="markers" />
            </NGi>
        </NGrid>
        <ChangeRequestStatusModal v-model:show="hasShowChangeRequestStatusModal" :patient-result="patientResult" />
    </AppLayout>
</template>

<style scoped>

</style>
