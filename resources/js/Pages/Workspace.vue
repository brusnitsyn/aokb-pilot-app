<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";
import {IconEdit, IconListSearch} from "@tabler/icons-vue";
import {Motion} from 'motion-v'
import {useCheckScope} from "@/Composables/useCheckScope.js";
import DepartmentModal from "@/Components/Department/DepartmentModal.vue";

const props = defineProps({
    diagnosis: Array,
    diagnosisGroups: Array,
    countResults: Number
})
const { hasScope, scopes } = useCheckScope()

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
                    <div class="relative">
                        <NAlert type="info" class="!rounded-3xl drop-shadow-sm">
                            <div class="leading-5">
                                Вы вошли как <span class="lowercase">{{ usePage().props.auth.user.role.name }}</span> «<span class="font-medium">{{ usePage().props.auth.user.department.name }}</span>»
                            </div>
                        </NAlert>
                        <div v-if="hasScope(scopes.HAS_CHANGE_DEPARTMENT)" class="absolute -right-[56px] top-0 flex items-center justify-center h-full">
                            <DepartmentModal />
                        </div>
                    </div>
                </NGi>
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
