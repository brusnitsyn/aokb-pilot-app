<script setup>
import {IconArrowLeft, IconArrowRight} from "@tabler/icons-vue";
import {router} from "@inertiajs/vue3";
import { Motion } from 'motion-v'

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
    selectedDepartmentId.value = model.value.department_id
    onNextStage()
}
</script>

<template>
    <Motion
        v-if="stage === 'department'"
        key="department"
        :initial="{ y: 100, opacity: 0 }"
        :animate="{ y: 0, opacity: 1 }"
        :exit="{ y: 100, opacity: 0 }">
        <NCard class="!rounded-3xl drop-shadow-sm">
            <template #header>
                В какую медицинскую организацию транспортировать?
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
                <NFormItem :show-label="false" path="department_id">
                    <NSelect v-model:value="model.department_id"
                             filterable placeholder="" :options="options" />
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
    </Motion>

    <Motion
        v-if="stage === 'department' && progress !== 100"
        :initial="{ y: 100 }"
        :animate="{ y: 0, scale: 1 }"
        :exit="{ y: -100, scale: 0 }">
        <NAlert class="!rounded-3xl drop-shadow-sm" type="info">
            <div class="leading-5">
                Выберите организацию
            </div>
        </NAlert>
    </Motion>
</template>

<style scoped>
:deep(.n-card__action) {
    @apply rounded-b-3xl py-4 px-6;
}
</style>
