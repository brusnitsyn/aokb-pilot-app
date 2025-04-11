<script setup>
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    patientResult: Object
})
const show = defineModel('show')
const hasLoading = ref(true)
const formRef = ref(null)
const form = useForm({
    status_id: null
})
const formData = computed(() => form.data())
const statuses = ref()
const rules = {
    status_id: {
        trigger: ['blur'],
        validator: () => {
            if (form.errors.hasOwnProperty('status_id')) {
                return new Error(form.errors.status_id)
            }
        }
    }
}
const onAfterEnter = async () => {
    hasLoading.value = true
    await axios.get(route('api.requests.statuses', {
        current_status_id: props.patientResult.status_id
    }, true)).then(({data}) => {
        statuses.value = data.statuses.map(item => {
            return {
                label: item.name,
                value: item.id
            }
        })
    }).finally(() => {
        hasLoading.value = false
    })
}

const onBeforeLeave = () => {
    form.reset()
    form.clearErrors()
}

const onSubmit = () => {
    form.put(route('patientResult.update', { patientResult: props.patientResult.id }), {
        onSuccess: () => {
            router.reload()
            form.reset()
            form.clearErrors()
            show.value = false
        },
        onError: (err) => {
            window.$message.error('Ошибка при сохранении')
            formRef.value?.validate()
            console.log(err)
        }
    })
}
</script>

<template>
    <NModal v-model:show="show"
            display-directive="if"
            class="max-w-screen-md rounded-3xl"
            preset="card"
            @submit="onSubmit"
            @after-enter="onAfterEnter"
            @before-leave="onBeforeLeave">
        <template #header>
            Изменение статуса запроса {{ patientResult.patient.number }}
        </template>
        <NSpin :show="hasLoading">
            <NForm ref="formRef" :model="formData" :rules="rules">
                <NGrid cols="2">
                    <NFormItemGi label="Новый статус" path="status_id">
                        <NSelect :options="statuses" v-model:value="form.status_id" />
                    </NFormItemGi>
                    <NFormItemGi span="2" :show-feedback="false">
                        <NButton type="primary" attr-type="submit">
                            Сохранить
                        </NButton>
                    </NFormItemGi>
                </NGrid>
            </NForm>
        </NSpin>
    </NModal>
</template>

<style scoped>

</style>
