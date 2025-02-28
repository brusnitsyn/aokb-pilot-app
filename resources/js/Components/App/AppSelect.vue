<script setup>
const themeOverrides = {
    Select: {
        peers: {
            InternalSelection: {
                borderRadius: '24px'
            },
            InternalSelectMenu: {
                borderRadius: '18px'
            },
        }
    }
}
</script>

<template>
    <NConfigProvider abstract :theme-overrides="themeOverrides">
        <NSelect v-bind="$attrs" />
    </NConfigProvider>
</template>

<style scoped>

</style>
