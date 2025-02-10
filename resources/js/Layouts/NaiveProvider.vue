<script setup>
import {ruRU, dateRuRU} from 'naive-ui'
import NMessageContent from "@/Components/Naive/NMessageContent.vue";

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
        // borderRadius: '24px'
    }
}
</script>

<template>
    <NDialogProvider />

    <NConfigProvider :locale="ruRU" :date-locale="dateRuRU" :theme-overrides="themeOverrides">
        <NMessageProvider keep-alive-on-hover>
            <NMessageContent />
        </NMessageProvider>
        <slot />
    </NConfigProvider>
</template>

<style scoped>

</style>
