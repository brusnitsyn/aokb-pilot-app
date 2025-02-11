<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    totalScore: [String, Number]
})
</script>

<template>
    <AppLayout>
        <NFlex align="center" class="max-w-xl mx-auto h-full">
            <NStatistic label="Результат" tabular-nums>
                <NNumberAnimation :from="0" :to="totalScore" />
            </NStatistic>
        </NFlex>
    </AppLayout>
</template>

<style scoped>

</style>
