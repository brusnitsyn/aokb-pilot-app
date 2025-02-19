<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    diagnosis: Array,
    diagnosisGroups: Array,
    countResults: Number
})

const page = usePage()

const onLogout = () => {
    window.$dialog.warning({
        title: 'Выход',
        content: 'Вы действительно хотите выйти?',
        positiveText: 'Выход',
        negativeText: 'Отмена',
        positiveButtonProps: {
            round: true,
            type: 'primary',
            secondary: true
        },
        negativeButtonProps: {
            round: true,
        },
        onPositiveClick: () => {
            router.post(route('logout'))
        },
    })
}
</script>

<template>
    <AppLayout title="Рабочая область">
        <NFlex align="center" class="max-w-xl mx-auto h-full">
            <NGrid cols="1" x-gap="16" y-gap="16" responsive="screen">
                <NGi>
                    <WorkspaceItem header="Создать запрос на транспортировку"
                                   image-url="/assets/svg/illustrations/request.svg"
                                   :href="route('request.create')"
                    />
                </NGi>
                <NGi>
                    <WorkspaceItem header="Запросы МО"
                                   image-url="/assets/svg/illustrations/my-requests.svg"
                                   :href="route('my.request')"
                                   :disabled="countResults === 0"
                                   disabled-reason="Пока нет активных запросов"
                    />
                </NGi>
                <NGi>
                    <WorkspaceItem header="Выход"
                                   image-url="/assets/svg/illustrations/exit.svg"
                                   @click="onLogout"
                                   type="error"
                    />
                </NGi>
            </NGrid>
        </NFlex>
    </AppLayout>
</template>
