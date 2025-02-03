<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { IconCirclePlus, IconTableDashed, IconDoorExit } from '@tabler/icons-vue'
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {usePage} from "@inertiajs/vue3";
const page = usePage()
const hasSelectDepartment = computed(() => page.props.activeDepartment)
</script>

<template>
    <AppLayout title="Dashboard">
        <NFlex vertical :size="20" class="max-w-xl mx-auto h-full" justify="space-between">
            <WorkspaceItem header="Создать запрос на транспортировку"
                           :href="route('request.create')"
                           :icon="IconCirclePlus"
                           :disabled="hasSelectDepartment === null"
                           disabled-reason="Выберите МО"
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
    </AppLayout>
</template>
