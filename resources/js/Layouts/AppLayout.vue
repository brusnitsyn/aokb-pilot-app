<script setup>
import { ref } from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import AppLogo from "@/Components/App/AppLogo.vue";
import {isExtraLargeScreen, isLargeScreen, isSmallScreen} from "@/Utils/mediaQuery.js";
import NaiveProvider from "@/Layouts/NaiveProvider.vue";
import {IconDoorExit, IconMenu3} from "@tabler/icons-vue";
import {NIcon} from "naive-ui";
import {Motion} from 'motion-v'

defineProps({
    title: String,
});

const user = usePage().props.auth.user
const departments = ref(usePage().props.departments)
const logReverb = ref()

const mobileMenuCollapsed = ref(false);
const renderIcon = (icon) => {
    return () => h(NIcon, null, {default: () => h(icon)})
}
const userOptions = [
    {
        label: 'Выйти из учетной записи',
        key: 'user-exit',
        icon: renderIcon(IconDoorExit),
        onClick: () => logout()
    },
]

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

onMounted(() => {
    if (user.department === null) return
    window.Echo.private(`${import.meta.env.VITE_REVERB_APP}.department.${user.department.id}`)
        .listen('PatientResultCreated', (data) => {
            logReverb.value = data
            window.$notify.info({
                title: `Новый запрос: ${data.patient.number}`,
                content: 'Ваша медицинская организация создала запрос.\nВы можете проверить его .'
            })
        })
})

</script>

<template>
    <Head :title="title" />
    <NaiveProvider>
        <NLayout position="absolute" class="!bg-transparent">
            <NLayoutHeader class="p-4 h-[74px]" bordered>
                <NFlex justify="space-between" align="center" class="h-full">
                    <NFlex class="h-full" align="center" :wrap="false" :size="isSmallScreen ? 16 : 30">
                        <Link :href="route('workspace')" class="h-full">
                            <AppLogo />
                        </Link>
                    </NFlex>
                    <NFlex align="center">
                        <NSpace vertical align="end" :size="0">
                            <NText>
                                {{ user.name }}
                            </NText>
                            <NText class="text-sm text-gray-400">
                                {{ user.email }}
                            </NText>
                        </NSpace>
                        <NAvatar :src="user.profile_photo_url" round size="large" />
                    </NFlex>
                </NFlex>
            </NLayoutHeader>
            <NLayout has-sider position="absolute" class="!bg-gray-50" style="top: 74px; bottom: 54px">
                <NLayout :native-scrollbar="false" content-class="p-4 lg:p-7 h-full" class="!bg-transparent h-full">
                    <Motion as="main"
                            class="h-full outline-none"
                            :initial="{
                              opacity: 0,
                              y: 100
                            }"
                            :animate="{
                              opacity: 1,
                              y: 0
                            }">
                        <NFlex v-if="$slots.header || $slots.headermore" justify="space-between" align="center"
                               class="mb-5">
                            <NH1 v-if="$slots.header" :class="headerClass">
                                <slot name="header"/>
                            </NH1>
                            <NSpace>
                                <slot name="headermore"/>
                            </NSpace>
                        </NFlex>
                        <NP v-if="$slots.subheader">
                            <slot name="subheader"/>
                        </NP>
                        <slot/>
                    </Motion>
                </NLayout>
            </NLayout>
            <NLayoutFooter
                bordered
                position="absolute"
                class="p-4 md:px-[24px]"
            >
                &copy;
                <a target="_blank" href="https://aokb28.su">Амурская областная клиническая больница</a>
                2025
            </NLayoutFooter>
        </NLayout>
    </NaiveProvider>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    @apply duration-200 transition-all;
}

.fade-enter-from,
.fade-leave-to {
    @apply opacity-0 scale-90 translate-y-10;
}
</style>
