<script setup>
import AppLayout from "@/Layouts/AppLayout.vue"
import { IconHome } from "@tabler/icons-vue"
import {Link, router} from "@inertiajs/vue3"
import DiagnosisForm from "@/Pages/Request/Partials/DiagnosisForm.vue";
import PersonalForm from "@/Pages/Request/Partials/PersonalForm.vue";
import DepartmentForm from "@/Pages/Request/Partials/DepartmentForm.vue";
import DepartmentQuestionsForm from "@/Pages/Request/Partials/DepartmentQuestionsForm.vue";
import PatientQuestionsForm from "@/Pages/Request/Partials/PatientQuestionsForm.vue";
const props = defineProps({
    diagnoses: Array,
    patientQuestions: Array,
    organizationQuestions: Array,
    medicalOrganizations: Array,
    selectedDiagnosisId: Number,
    departments: Array
});

const personalPatient = ref({
    first_name: null,
    last_name: null,
    middle_name: null,
    date_birth: null,
    snils: null
});
const selectedDepartmentId = ref(null);
const patientResponses = ref({});

const validationMessage = 'Это поле обязательное!'

const stages = [
    'personal',
    'diagnosis',
    'department',
    'department-questions',
    'patient-questions'
]

const stageIndex = ref(0)

const hasPrevStage = computed(() => stageIndex.value > 0)
const hasNextStage = computed(() => stageIndex.value < stages.length)

const onPrevStage = () => {
    if (hasPrevStage.value) {
        stageIndex.value--
    }
}
const onNextStage = () => {
    if (hasNextStage.value) {
        stageIndex.value++
    }
}

const stage = computed({
    get() {
        return stages[stageIndex.value]
    },
    set(value) {
        if (Number.isInteger(value)) {
            stageIndex.value = value
        } else {
            stageIndex.value = stages.findIndex(itm => itm === value)
        }
    }
})

const onSubmit = () => {
    router.post(route('request.store'), {
        patient: personalPatient.value,
        diagnosis_id: props.selectedDiagnosisId,
        medical_organization_id: selectedDepartmentId.value,
        patient_responses: patientResponses.value
    }, {
        onSuccess: () => {
            window.$message.success('Ответы успешно отправлены!');
        },
        onError: () => {
            window.$message.error('Произошла ошибка при отправке ответов.');
        }
    })
};

provide('navigate', { hasPrevStage, hasNextStage, onPrevStage, onNextStage, onSubmit })
</script>
<template>
    <AppLayout title="Создание запроса на транспортировку">
        <NFlex vertical justify="items-start" size="large" class="max-w-xl mx-auto h-full">
            <div>
                <Link :href="route('workspace')" class="h-full">
                    <NButton secondary round>
                        <template #icon>
                            <NIcon :component="IconHome" />
                        </template>
                        Вернуться на рабочую область
                    </NButton>
                </Link>
            </div>

            <!-- Заполнение персональных данных пациента -->
            <PersonalForm v-model:personal-patient="personalPatient"
                          :stage="stage"
                          :validation-message="validationMessage" />

            <!-- Установка диагноза пациента -->
            <DiagnosisForm :stage="stage"
                           :diagnoses="diagnoses"
                           :validation-message="validationMessage" />

            <!-- Установка МО -->
            <DepartmentForm :stage="stage"
                            v-model:selected-department-id="selectedDepartmentId"
                            :departments="departments"
                            :validation-message="validationMessage" />

            <!-- Настройка параметров МО -->
            <DepartmentQuestionsForm :stage="stage"
                                     :department-questions="organizationQuestions"
                                     :validation-message="validationMessage" />

            <!-- Вопросы о пациенте -->
            <PatientQuestionsForm v-model:patient-responses="patientResponses"
                                  :stage="stage"
                                  :patient-questions="patientQuestions"
                                  :validation-message="validationMessage" />
        </NFlex>
    </AppLayout>
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
