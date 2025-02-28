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
                class="py-3 px-4 cursor-pointer transition-colors leading-4 rounded-3xl" :class="hasActive(key) ? 'bg-[#EC6608] text-[#EC6608] bg-opacity-20' : ''"
                @click="onSelect(key, option)">
                {{ key }}
            </li>
        </ul>
    </NScrollbar>
</template>

<style scoped>

</style>
