<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { IconCirclePlus, IconTableDashed, IconDoorExit } from '@tabler/icons-vue'
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";
defineProps({
    diagnosis: Array
})
const page = usePage()
const hasSelectDepartment = computed(() => page.props.activeDepartment)
const hasShowPrepareModal = ref(false)
const diagnosisId = ref(null)

function onSubmit() {
    router.get(route('request.create'), {
        diagnosis_id: diagnosisId.value
    })
}

function onShowPrepareModal() {
    hasShowPrepareModal.value = true
}
</script>

<template>
    <AppLayout title="Dashboard">
        <NFlex vertical :size="20" class="max-w-xl mx-auto h-full" justify="space-between">
            <WorkspaceItem header="Создать запрос на транспортировку"
                           :icon="IconCirclePlus"
                           :disabled="hasSelectDepartment === null"
                           disabled-reason="Выберите МО"
                           @click="onShowPrepareModal"
            />
            <WorkspaceItem header="Мои запросы"
                           :href="route('request.create')"
                           :icon="IconTableDashed"
            />
            <WorkspaceItem header="Выход"
                           :href="route('request.create')"
                           :icon="IconDoorExit"
                           type="error"
            />
        </NFlex>
        <NModal :mask-closable="false"
                display-directive="if"
                v-model:show="hasShowPrepareModal"
                preset="card"
                class="max-w-screen-md"
                title="Подготовка к запросу">
            <NForm @submit.prevent="onSubmit">
                <NFormItem label="Выберите диагноз">
                    <NSelect
                        v-model:value="diagnosisId"
                        :options="diagnosis"
                        label-field="name"
                        value-field="id"
                    />
                </NFormItem>
                <NButton type="primary" attr-type="submit" :disabled="diagnosis_id === null">
                    Создать запрос
                </NButton>
            </NForm>
        </NModal>
    </AppLayout>
</template>
