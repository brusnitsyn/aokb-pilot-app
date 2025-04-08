<script setup>
import {IconArrowLeft} from "@tabler/icons-vue";
import {router} from "@inertiajs/vue3";
const props = defineProps({
    parentRoute: String
})
const onBack = () => {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        router.visit(props.parentRoute, {
            preserveState: true,
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <NButton type="default" secondary text @click="onBack">
        Назад
        <template #icon>
            <NIcon :component="IconArrowLeft" />
        </template>
    </NButton>
</template>

<style scoped>

</style>
