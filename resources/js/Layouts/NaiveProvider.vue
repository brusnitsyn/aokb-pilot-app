<script setup>
import {ruRU, dateRuRU} from 'naive-ui'
import NMessageContent from "@/Components/Naive/NMessageContent.vue";
import NDialogContent from "@/Components/Naive/NDialogContent.vue";
import NNotificationContent from "@/Components/Naive/NNotificationHelperContent.vue";

const themeOverrides = {
    common: {
        primaryColor: '#EC6608',
        primaryColorHover: '#f3731a',
        primaryColorPressed: '#dd6918',
        primaryColorSuppl: '#cf5e0f'
    },
    Button: {
        // borderRadiusMedium: '24px',
    },
    Dialog: {
        borderRadius: '24px'
    },
    Modal: {

    }
}
</script>

<template>

    <NConfigProvider :locale="ruRU" :date-locale="dateRuRU" :theme-overrides="themeOverrides">
        <NMessageProvider keep-alive-on-hover>
            <NMessageContent />
        </NMessageProvider>
        <NDialogProvider>
            <NDialogContent />
        </NDialogProvider>
        <NNotificationProvider key="helper" placement="bottom-right" container-class="!min-w-xl !rounded-3xl" class="!rounded-3xl">
            <NNotificationContent />
        </NNotificationProvider>
        <slot />
    </NConfigProvider>
</template>

<style>
.n-notification {
    @apply !rounded-3xl
}
</style>
