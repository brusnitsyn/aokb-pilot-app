<script setup>
import { ref } from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import DepartmentModal from "@/Components/Department/DepartmentModal.vue";
import AppLogo from "@/Components/App/AppLogo.vue";
import {isSmallScreen} from "@/Utils/mediaQuery.js";

defineProps({
    title: String,
});

const departments = ref(usePage().props.departments)

const showingNavigationDropdown = ref(false);

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
</script>

<template>
    <div>
        <Head :title="title" />

        <NLayout position="absolute" class="!bg-transparent">
            <NLayoutHeader class="p-4 h-[74px]" bordered>
                <NFlex justify="space-between" align="center" class="h-full">
                    <NFlex class="h-full" align="center" :wrap="false" :size="isSmallScreen ? 16 : 30">
                        <Link :href="route('workspace')" class="h-full">
                            <AppLogo />
                        </Link>
                        <DepartmentModal :departments="departments" />
                    </NFlex>
                    <NSpace class="-m-5 -mr-[24px]" :size="0">
                        <NDropdown v-if="user && isLargeScreen" trigger="click" placement="top-end" :options="userOptions" @select="(key, option) => option.onClick()">
                            <NButton quaternary class="h-[73px] rounded-none">
                                <NSpace align="center">
                                    <NSpace vertical align="end" :size="2">
                                        <NText class="font-semibold">
                                            {{ user.name }}
                                        </NText>
                                        <NText>
                                            {{ user.login }}
                                        </NText>
                                    </NSpace>
                                    <NAvatar :src="user.profile_photo_url" round size="large" />
                                </NSpace>
                            </NButton>
                        </NDropdown>
                        <NButton v-if="!isLargeScreen" quaternary class="h-[61px] w-[61px] rounded-none" @click="mobileMenuCollapsed = true">
                            <NIcon :component="IconMenu3" />
                        </NButton>
                    </NSpace>
                </NFlex>
            </NLayoutHeader>
            <NLayout has-sider position="absolute" class="!bg-transparent" style="top: 73px; bottom: 54px">
                <NLayoutSider v-if="isLargeScreen" collapse-mode="width" :collapsed-width="0" width="240" :collapsed="menuCollapsed"
                              show-trigger @collapse="menuCollapsed = true"
                              @expand="menuCollapsed = false"
                              :collapsed-trigger-class="menuCollapsed === true ? '!-right-5 !top-1/4' : ''"
                              trigger-class="!top-1/4" bordered content-class="">
                    <NFlex vertical justify="space-between" class="h-full relative">
                        <NMenu :options="menuOptions" :value="currentRoute"/>
                        <NSpace vertical>
                            <NImage v-if="characterView" :preview-disabled="true" class="h-[450px] p-8 px-8" width="192" src="/assets/svg/woman.svg" />
                            <NImage v-if="heartView" :preview-disabled="true" src="/assets/svg/heart.svg" width="192" class="absolute bottom-[15%] px-6" />
                        </NSpace>
                    </NFlex>
                </NLayoutSider>
                <NLayout :native-scrollbar="false" :content-class="(menuCollapsed && isLargeScreen) ? 'p-7 pl-14' : 'p-4 lg:p-7'" class="!bg-transparent">
                    <Transition name="fade" mode="out-in" appear>
                        <main>
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
                        </main>
                    </Transition>
                </NLayout>
            </NLayout>
            <NLayoutFooter
                bordered
                position="absolute"
                class="p-4 px-[24px]"
            >
                &copy;
                <Link href="https://aokb28.su">Амурская областная клиническая больница</Link>
                2025
            </NLayoutFooter>
        </NLayout>
    </div>
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
