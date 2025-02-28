<script setup>
const value = defineModel('value`')
</script>

<template>
    <NInput v-model:value="value" v-mask="'###-###-### ##'" placeholder="" class="!px-1.5 !rounded-3xl" />
</template>

<style scoped>

</style>
