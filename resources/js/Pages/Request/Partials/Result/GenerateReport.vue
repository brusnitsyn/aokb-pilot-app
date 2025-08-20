<script setup>
import MarkdownRenderer from "@/Components/Markdown/MarkdownRenderer.vue";
import {usePage} from "@inertiajs/vue3";
import {IconBlockquote} from '@tabler/icons-vue'
const show = defineModel('show')
const loading = ref(true)
const content = ref('')
const fetchReport = () => {
    loading.value = true
    window.axios.get(route('ai.resume', {
        patient_result_id: usePage().props.patientResult.id
    })).then(res => {
        content.value = res.data.content
    }).finally(() => {
        loading.value = false
    })
}

const onAfterLeave = () => {
    loading.value = true
    content.value = ''
}
</script>

<template>
    <NDrawer @after-enter="fetchReport" @after-leave="onAfterLeave" v-model:show="show" placement="right" :width="500" class="!rounded-l-3xl">
        <NDrawerContent>
            <template #header>
                <NFlex align="center">
                    <NIcon :component="IconBlockquote" :size="18" /> Резюмировать
                </NFlex>
            </template>
            <NSpace v-if="loading" vertical>
                <NSkeleton :width="280" round />
                <NSkeleton :width="180" round />
                <NSkeleton :width="180" round />
                <NSkeleton round />
                <NSkeleton round />
                <NSkeleton round />
                <NSkeleton round />
            </NSpace>
            <MarkdownRenderer v-else :content="content" />
        </NDrawerContent>
    </NDrawer>
</template>

<style scoped>

</style>
