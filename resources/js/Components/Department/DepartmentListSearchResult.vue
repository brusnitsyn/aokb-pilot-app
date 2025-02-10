<script setup>
const props = defineProps({
    options: {
        type: Array
    }
})
const selected = defineModel('selected')
function onSelect(key, option) {
    selected.value = option
}
</script>

<template>
    <NSpace vertical>
        <NH5 class="!mb-0">
            Результаты поиска
        </NH5>
        <NScrollbar class="max-h-[300px]">
            <ul>
                <li v-for="(option, key) in options" :key="option.id"
                    class="py-3 px-4 cursor-pointer rounded leading-4 transition-colors hover:text-[#EC6608]"
                    @click="onSelect(key, option)">
                    {{ option.name }}
                </li>
            </ul>
        </NScrollbar>
    </NSpace>
</template>

<style scoped>

</style>
