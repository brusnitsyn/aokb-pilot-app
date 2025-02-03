<script setup>
const props = defineProps({
    options: {
        type: Object
    },
})

const selected = defineModel('selected')
function onSelect(key, option) {
    selected.value = { key, children: option }
}

function hasActive(key) {
    if (key === selected.value.key) return true

    return false
}
</script>

<template>
    <NScrollbar class="max-h-[300px]">
        <ul>
            <li v-for="(option, key) in options" :key="option.id"
                class="py-3 px-4 cursor-pointer rounded transition-colors leading-4" :class="hasActive(key) ? 'bg-green-300 text-green-600 bg-opacity-60' : ''"
                @click="onSelect(key, option)">
                {{ key }}
            </li>
        </ul>
    </NScrollbar>
</template>

<style scoped>

</style>
