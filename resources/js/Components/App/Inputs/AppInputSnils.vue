<script setup>
const value = defineModel('value`')
</script>

<template>
    <NInput v-model:value="value" v-mask="'###-###-### ##'" placeholder="" />
</template>

<style scoped>

</style>
