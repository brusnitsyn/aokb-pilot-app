<script setup>
import {isMediumScreen, isSmallScreen} from "@/Utils/mediaQuery.js";

const props = defineProps({
    options: {
        type: Object
    },
})

const scrollbarClass = [
    isSmallScreen.value ? 'h-full' : '',
    isSmallScreen.value === false ? 'max-h-[300px]' : ''
]
const selected = defineModel('selected')
function onSelect(key, option) {
    selected.value = option
}

function hasActive(key) {
    if (selected.value === null || typeof selected.value === 'undefined') return false
    if (key === selected.value.id) return true

    return false
}
</script>

<template>
    <NScrollbar :class="scrollbarClass">
        <ul>
            <li v-for="(option, key) in options" :key="option.id"
                class="py-3 px-4 cursor-pointer rounded transition-colors leading-4" :class="hasActive(option.id) ? 'bg-green-300 text-green-600 bg-opacity-60' : ''"
                @click="onSelect(key, option)">
                {{ option.name }}
            </li>
        </ul>
    </NScrollbar>
</template>

<style scoped>

</style>
