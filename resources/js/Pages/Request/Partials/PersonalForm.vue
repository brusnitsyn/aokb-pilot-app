<script setup>
import {IconArrowRight} from "@tabler/icons-vue";
import AppDatePicker from "@/Components/App/Inputs/AppDatePicker.vue";
import AppInputSnils from "@/Components/App/Inputs/AppInputSnils.vue";
const props = defineProps(['stage', 'validationMessage'])
const { onNextStage } = inject('navigate')
// Ссылка на форму персональных данных пациента
const formRef = ref()
// Модель персональных данных пациента
const model = defineModel('personalPatient')
// Правила заполнения персональных данных пациента
const rules = {
    first_name: {
        required: true,
        trigger: ['input', 'blur'],
        message: props.validationMessage
    },
    last_name: {
        required: true,
        trigger: ['input', 'blur'],
        message: props.validationMessage
    },
    middle_name: {
        required: true,
        trigger: ['input', 'blur'],
        message: props.validationMessage
    },
    date_birth: {
        type: 'number',
        required: true,
        trigger: ['input', 'blur'],
        message: props.validationMessage
    },
    'snils': [
        {
            required: true,
            trigger: ['input', 'blur'],
            message: props.validationMessage,
        },
        {
            min: 14,
            message: 'Неверный номер СНИЛС!',
            trigger: ['blur', 'input']
        }
    ]
}

// Прогресс заполнения персональных данных пациента
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

const onNext = () => {
    formRef.value?.validate((errors) => {
        if (!errors) {
            onNextStage()
        } else {
            window.$message.error('Проверьте правильность заполнения формы')
        }
    })
}
</script>

<template>
    <transition name="fade" mode="out-in">
        <NCard v-if="stage === 'personal'" key="personal" class="!rounded-3xl drop-shadow-sm">
            <template #header>
                Персональные данные пациента
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
                <NFormItem label="Фамилия" path="last_name">
                    <NInput v-model:value="model.last_name" placeholder="" />
                </NFormItem>
                <NFormItem label="Имя" path="first_name">
                    <NInput v-model:value="model.first_name" placeholder="" />
                </NFormItem>
                <NFormItem label="Отчество" path="middle_name">
                    <NInput v-model:value="model.middle_name" placeholder="" />
                </NFormItem>
                <NFormItem label="Дата рождения" path="date_birth">
                    <AppDatePicker v-model:value="model.date_birth" />
                </NFormItem>
                <NFormItem label="СНИЛС" path="snils">
                    <AppInputSnils v-model:value="model.snils" />
                </NFormItem>
            </NForm>
            <template #action>
                <NButtonGroup class="flex justify-end">
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
        <NAlert v-if="stage === 'personal' && progress !== 100" class="!rounded-3xl drop-shadow-sm" type="info">
            <div class="leading-5">
                Укажите персональные данные пациента
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
