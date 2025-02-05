<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    totalScore: [String, Number]
})
</script>

<template>
    <AppLayout>
        Результат
        {{ totalScore }}
    </AppLayout>
</template>

<style scoped>

</style>
