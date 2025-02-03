<script setup>
import {isMediumScreen, isSmallScreen} from "@/Utils/mediaQuery.js";

const props = defineProps({
    options: {
        type: Array
    }
})
const scrollbarClass = [
    isSmallScreen.value === true ? 'h-full' : '',
    isMediumScreen.value === true ? 'max-h-[300px]' : ''
]
const selected = defineModel('selected')
function onSelect(key, option) {
    selected.value = option
}
</script>

<template>
    <NScrollbar :class="scrollbarClass">
        <ul>
            <li v-for="(option, key) in options" :key="option.id"
                class="py-3 px-4 cursor-pointer rounded leading-4 transition-colors hover:text-green-500"
                @click="onSelect(key, option)">
                {{ option.name }}
            </li>
        </ul>
    </NScrollbar>
</template>

<style scoped>

</style>
