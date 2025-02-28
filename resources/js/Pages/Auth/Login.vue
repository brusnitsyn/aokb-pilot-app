<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import NaiveProvider from "@/Layouts/NaiveProvider.vue";
import AuthenticationCard from "@/Pages/Auth/Partials/AuthenticationCard.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    login: '',
    password: '',
    remember: true,
});
const formRef = ref()
const defaultMessage = 'Это поле обязательное'
const rules = {
    login: [
        {
            required: true,
            message: defaultMessage,
            trigger: ['blur', 'input']
        }
    ],
    password: [
        {
            required: true,
            message: defaultMessage,
            trigger: ['blur', 'input'],
        }
    ]
}
function submit(e) {
    e.preventDefault()
    formRef.value?.validate(
        (errors) => {
            if (!errors) {
                form.transform(data => ({
                    ...data,
                    remember: form.remember ? 'on' : '',
                })).post(route('login'), {
                    onFinish: () => form.reset('password'),
                });
            }
            else {
                console.log(errors)
            }
        }
    )
}
</script>

<template>
    <Head title="Авторизация" />

    <NaiveProvider>
        <AuthenticationCard>
            <template #logo>
                <NSpace vertical align="center" :size="16">
                    <NImage src="/assets/svg/logo-short.svg" preview-disabled />
                    <div class="rounded-3xl bg-white py-3 px-4 drop-shadow-sm text-[#ec6608] font-medium">
                        Программа транспортировки пациентов
                    </div>
                </NSpace>
            </template>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <NForm @submit.prevent="submit" :model="form" :rules="rules" ref="formRef">
                <NFormItem label="Имя пользователя" path="login">
                    <NInput v-model:value="form.login" size="large" placeholder="" round />
                </NFormItem>
                <NFormItem label="Пароль" path="password">
                    <NInput type="password" size="large" v-model:value="form.password" show-password-on="click" placeholder="" round />
                </NFormItem>

                <NButton class="mt-4" type="primary" size="large" block :loading="form.processing" :disabled="form.processing" attr-type="submit" round>
                    Войти
                </NButton>
            </NForm>
        </AuthenticationCard>
    </NaiveProvider>
</template>

<style scoped>

</style>
