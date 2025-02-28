<script setup>
const themeOverrides = {
    Input: {
        borderRadius: '24px'
    }
}
</script>

<template>
    <NConfigProvider abstract :theme-overrides="themeOverrides">
        <NInput v-bind="$attrs" />
    </NConfigProvider>
</template>

<style scoped>

</style>
