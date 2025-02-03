<script setup>
import {debounce} from "@/Utils/debounce.js";

const value = ref('')
const emits = defineEmits(['update:options'])
const hasLoading = ref(false)
const debounceValue = computed({
    get() {
        return value.value
    },
    async set(search) {
        value.value = search
        debounce(async () => await searchDepartment(value.value), 400)
    }
})

async function searchDepartment(search) {
    hasLoading.value = true

    // Если пустой запрос = обнулить результат
    if (search === '') {
        emits('update:options', [])
        hasLoading.value = false
        return
    }

    await axios.post('/api/v1/departments/search', {
        search
    }).then((res) => {
        emits('update:options', res.data)
        hasLoading.value = false
    }).finally(() => {
        hasLoading.value = false
    })
}
</script>

<template>
    <NInput v-model:value="debounceValue"
            :loading="hasLoading"
            clearable
            :disabled="hasLoading"
            placeholder="Найти медицинскую организацию"
            size="large"
    />
</template>

<style scoped>

</style>
