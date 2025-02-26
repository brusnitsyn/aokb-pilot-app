<script setup>
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";
import {Motion} from 'motion-v'
const hasShow = ref(false)
const departmentId = usePage().props.auth.user.department.id
const department_id = ref(departmentId)
const comment = ref(null)

const onSubmit = async () => {
    await axios.post(route('user.department.update'), {
        id: department_id.value,
        comment: comment.value
    }).then(() => {
        hasShow.value = false
        router.visit(route('request.create'))
    })


}
</script>

<template>
    <WorkspaceItem header="Создать запрос на транспортировку"
                   image-url="/assets/svg/illustrations/request.svg"
                   @click="hasShow = true"
    />
    <NModal v-model:show="hasShow"
            :mask-closable="false"
            display-directive="if"
            class="max-w-xl !rounded-3xl"
            preset="card"
            title="Требуется ли вылет по координатам">
        <NSpace vertical :size="24">
            <NForm @submit.prevent="onSubmit">
                <NFormItemGi :show-label="false" :show-feedback="false">
                    <NRadioGroup v-model:value="department_id" class="flex flex-col gap-y-2">
                        <NRadio :value="30" label="Да" />
                        <NRadio :value="departmentId" label="Нет" />
                    </NRadioGroup>
                </NFormItemGi>
                <Motion v-if="department_id === 30"
                        :initial="{ opacity: 0 }"
                        :animate="{ opacity: 1 }"
                        :exit="{ opacity: 0 }">
                    <NFormItemGi label="Комментарий" :show-feedback="false" class="mt-4">
                        <NInput type="textarea" v-model:value="comment" placeholder="" :resizable="false" />
                    </NFormItemGi>
                </Motion>
                <NButton type="primary" round size="large" block attr-type="submit" class="mt-6">
                    Перейти к созданию запроса
                </NButton>
            </NForm>

        </NSpace>
    </NModal>
</template>

<style scoped>

</style>
