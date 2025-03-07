<script setup>
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";
import {Motion} from 'motion-v'

const department = computed(() => usePage().props.auth.user.department)
const hasSetDepartment = computed(() => department.value !== null)

const hasShow = ref(false)
const department_id = ref(null)
const departmentId = computed(() => {
    if (department.value) {
        department_id.value = department.value.id
        return department.value.id
    }
    return null
})

const comment = ref(null)

const onSubmit = async () => {
    await axios.post(route('user.department.update'), {
        id: department_id.value,
        sender_department_id: usePage().props.auth.user.department.id,
        comment: comment.value
    }).then(() => {
        hasShow.value = false
        router.visit(route('request.create'))
    })
}

const onClickItem = () => {
    if (hasSetDepartment.value === true)
        hasShow.value = true
}

const placeholder = ref('Опишите подробнее о месте эвакуации')
const hasShowAnswers = ref(true)

const hasDisabledSubmit = computed(() => {
    if (department_id.value === 30) {
        hasShowAnswers.value = false
        if (typeof comment.value === 'undefined' || comment.value === null)
            return true
        if (comment.value.match(/^ *$/) !== null) return true
    }
    else
        return false
})

const onAfterLeave = () => {
    department_id.value = departmentId.value
    comment.value = null
    hasShowAnswers.value = true
}
</script>

<template>
    <WorkspaceItem header="Создать запрос на транспортировку"
                   image-url="/assets/svg/illustrations/request.svg"
                   @click="onClickItem"
                   :disabled="!hasSetDepartment"
                   disabled-reason="Выберите МО"
    />
    <NModal v-model:show="hasShow"
            :mask-closable="false"
            display-directive="if"
            class="max-w-xl !rounded-3xl"
            preset="card"
            @after-leave="onAfterLeave">
        <template #header>
            <div v-if="department_id === 30" class="!text-base">
                Транспортировка будет осуществлена по координатам
            </div>
            <div v-else class="!text-base">
                Транспортировка будет осуществлена из «{{ department.name }}»
            </div>
        </template>
        <NSpace vertical :size="24">
            <NForm model="" @submit.prevent="onSubmit">
                <NFormItemGi v-if="hasShowAnswers" :show-label="false" :show-feedback="false">
                    <NRadioGroup v-model:value="department_id" class="flex flex-row gap-x-2 items-center justify-center w-full">
                        <NRadio :value="departmentId" label="Да" />
                        <NRadio :value="30" label="Нет" />
                    </NRadioGroup>
                </NFormItemGi>
                <Motion v-if="department_id === 30"
                        :initial="{ opacity: 0 }"
                        :animate="{ opacity: 1 }"
                        :exit="{ opacity: 0 }">
                    <NFormItemGi label="Комментарий" class="mt-2">
                        <NInput class="rounded-2xl px-1" type="textarea" v-model:value="comment" :placeholder="placeholder" @focus="placeholder = ''" @blur="placeholder = 'Опишите подробнее о месте эвакуации'" :resizable="false"  />
                    </NFormItemGi>
                </Motion>
                <NButton type="primary" round size="large" block attr-type="submit" class="mt-3" :disabled="hasDisabledSubmit">
                    Далее
                </NButton>
            </NForm>

        </NSpace>
    </NModal>
</template>

<style scoped>

</style>
