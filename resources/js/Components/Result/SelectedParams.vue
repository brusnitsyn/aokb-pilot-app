<script setup>
const props = defineProps({
    params: Array,
    max: {
        type: Number,
        default: 1
    }
})

const visibleParamsMax = computed(() => props.params.length === props.max ? 1 : props.max)
const visibleParams = computed(() => props.params.slice(0, visibleParamsMax.value))
const paramsClipCount = computed(() => props.params.length - visibleParamsMax.value)
</script>

<template>
    <NFlex :size="6">
        <NTag v-if="params.length === max" v-for="param in visibleParams" round type="primary">
            {{ param.text }}
        </NTag>
        <NTag v-else v-for="param in visibleParams" round type="primary">
            {{ param.text }}
        </NTag>
        <NPopover v-if="params.length > visibleParamsMax" overlap :show-arrow="false" placement="right" class="!rounded-3xl">
            <template #trigger>
                <NTag round type="primary">
                    +{{ paramsClipCount }}
                </NTag>
            </template>
            <NFlex :size="6">
                <NTag v-for="param in params" round type="primary">
                    {{ param.text }}
                </NTag>
            </NFlex>
        </NPopover>
    </NFlex>
</template>

<style scoped>

</style>
