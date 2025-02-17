<script setup>
import {IconArrowLeft, IconArrowRight} from "@tabler/icons-vue";
import {router} from "@inertiajs/vue3";
import { AnimatePresence, Motion } from 'motion-v'

const props = defineProps(['stage', 'departments', 'validationMessage'])
const { hasPrevStage, onPrevStage, onNextStage } = inject('navigate')
// Ссылка на форму
const formRef = ref()
// Модель
const model = ref({
    department_id: null,
})
const selectedDepartmentId = defineModel('selectedDepartmentId')
// Правила заполнения
const rules = {
    department_id: {
        type: 'number',
        required: true,
        trigger: ['change', 'blur'],
        message: props.validationMessage
    }
}

// Прогресс заполнения
const progress = computed(() => {
    const filled = Object.values(model.value).filter(item => {
        return item !== null && item !== ''
    }).length;
    const total = Object.keys(model.value).length;
    const result = (filled / total) * 100
    return Number(result.toLocaleString(undefined, {
        maximumFractionDigits: 0
    }));
});

const options = computed({
    get() {
        if(props.departments !== undefined) {
            return props.departments.map(item => {
                return {
                    value: item.department.id,
                    label: item.department.name
                }
            })
        }

        return []
    }
})

const onNext = async () => {
    await axios.post(route('user.department.update'), {
        id: model.value.department_id
    }).then(() => {
        router.reload()
        selectedDepartmentId.value = model.value.department_id
        onNextStage()
    })
}
</script>

<template>
    <AnimatePresence>
        <Motion
            v-if="stage === 'department'"
            class="bg-primary  aspect-square rounded-2xl"
            :initial="{ scale: 0 }"
            :animate="{ rotate: 180, scale: 1 }"
            :exit="{ rotate: 0, scale: 0 }">

        </Motion>
    </AnimatePresence>
    <transition name="fade" mode="out-in">
        <NCard v-if="stage === 'department'" key="department" class="!rounded-3xl drop-shadow-sm">
            <template #header>
                Выберите медицинскую организацию
            </template>
            <template #cover>
                <NProgress
                    type="line"
                    :percentage="progress"
                    color="#EC6608"
                    :indicator-placement="'inside'"
                    status="success"
                    height="24px"
                    border-radius="24px 24px 0px 0px"
                    :fill-border-radius="progress >= 100 ? '0px 24px 0px 0px' : '0px 24px 24px 0px'"
                />
            </template>
            <NForm ref="formRef"
                   :model="model"
                   :rules="rules">
                <NFormItem label="Выберите медицинскую организацию" path="department_id">
                    <NSelect v-model:value="model.department_id"
                             placeholder="" :options="options" />
                </NFormItem>
            </NForm>
            <template #action>
                <NButtonGroup class="flex justify-end">
                    <NButton v-if="hasPrevStage"
                             secondary
                             round
                             @click="onPrevStage">
                        <template #icon>
                            <NIcon :component="IconArrowLeft" />
                        </template>
                        Назад
                    </NButton>
                    <NButton
                        type="primary"
                        secondary
                        round
                        :disabled="progress !== 100"
                        @click="onNext"
                        icon-placement="right"
                    >
                        <template #icon>
                            <NIcon :component="IconArrowRight" />
                        </template>
                        Далее
                    </NButton>
                </NButtonGroup>
            </template>
        </NCard>
    </transition>
    <transition name="fade" mode="out-in">
        <NAlert v-if="stage === 'department' && progress !== 100" class="!rounded-3xl drop-shadow-sm" type="info">
            <div class="leading-5">
                Выберите медицинскую организацию
            </div>
        </NAlert>
    </transition>
</template>

<style scoped>
:deep(.n-card__action) {
    @apply rounded-b-3xl py-4 px-6;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
