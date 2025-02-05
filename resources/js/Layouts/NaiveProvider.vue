<script setup>
import {ruRU, dateRuRU} from 'naive-ui'
import NMessageContent from "@/Components/Naive/NMessageContent.vue";
</script>

<template>
    <NDialogProvider />

    <NConfigProvider :locale="ruRU" :date-locale="dateRuRU">
        <NMessageProvider keep-alive-on-hover>
            <NMessageContent />
        </NMessageProvider>
        <slot />
    </NConfigProvider>
</template>

<style scoped>

</style>
