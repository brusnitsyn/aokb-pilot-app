<script setup>
import DepartmentSelectRegion from "@/Components/Department/DepartmentSelectRegion.vue"
import DepartmentSelectDepartment from "@/Components/Department/DepartmentSelectDepartment.vue"
import DepartmentInputSearch from "@/Components/Department/DepartmentInputSearch.vue"
import DepartmentListSearchResult from "@/Components/Department/DepartmentListSearchResult.vue"
import {isExtraLargeScreen, isLargeScreen, isMediumScreen, isSmallScreen} from "@/Utils/mediaQuery.js"
import {router, usePage} from "@inertiajs/vue3"
import {IconMapPin, IconSettings} from '@tabler/icons-vue'

const emits = defineEmits([
    'clickOnShowMoParameters'
])
const departments = ref([])

axios.get('/api/v1/departments').then(res => {
    departments.value = res.data
    selectedRegion.value = usePage().props.activeDepartment ? {
        key: usePage().props.activeDepartment.region,
        children: departments.value[usePage().props.activeDepartment.region]
    } : {
        key: '',
        children: null,
    }
})
const hasActiveDepartment = computed(() => usePage().props.activeDepartment !== null)
const hasShowModal = ref(false)
const hasLoading = ref(false)
const selectedRegion = ref({
    key: '',
    children: null,
})
const departmentActive = computed({
    get() {
        return usePage().props.activeDepartment
    },
    async set(value) {
        hasLoading.value = true
        await axios.post(route('user.department.update'), {
            ...value
        }).then(() => {
            hasShowModal.value = false
            router.reload()
        }).finally(() => {
            hasLoading.value = false
        })
    }
})
const searchedOptions = ref([])

const modalClass = computed(() => {
    return [
        '!rounded-3xl',
        isSmallScreen.value === true ? 'h-screen' : '',
        isMediumScreen.value === true
        || isLargeScreen.value === true
        || isExtraLargeScreen.value ? 'max-w-screen-md h-[535px]' : ''
    ]
})

const modalTitle = computed(() => {
    if (isSmallScreen.value === true) {
        return 'Выберите МО'
    } else {
        return 'Выберите медицинскую организацию'
    }
})

const hasWorkspacePage = computed(() => usePage().url.includes('workspace'))

// Проверка, выбраны ли группа и диагноз
const hasSelectedDiagnosis = computed(() => {
    return usePage().props.selectedDiagnosisGroup !== null && usePage().props.selectedDiagnosis !== null
})


const departmentActiveClass = computed(() => {
    return [
        '!my-0',
        hasWorkspacePage.value ? 'cursor-pointer hover:text-[#EC6608]' : '',
        isSmallScreen.value ? '!text-base !leading-5' : '',
        isMediumScreen.value ? '!text-lg' : '',
        isLargeScreen.value
        || isExtraLargeScreen.value ? '!text-lg' : ''
    ]
})


function onClickTitle() {
    if (hasWorkspacePage.value)
        hasShowModal.value = true
}

function onShowDrawer() {
    emits('clickOnShowMoParameters')
}

function onGetSearchResult(options) {
    searchedOptions.value = options
}

function onLeaveModal() {
    searchedOptions.value = []
}
</script>

<template>
    <NFlex align="center" inline>
        <NButtonGroup>
            <NButton round type="primary" secondary @click="onClickTitle" :disabled="(!hasWorkspacePage || !hasSelectedDiagnosis)">
                <template #icon>
                    <NIcon :component="IconMapPin" />
                </template>
                {{ departmentActive ? departmentActive.name : 'Выберите МО' }}
            </NButton>
            <NButton v-if="hasActiveDepartment"
                     round
                     type="info"
                     secondary
                     @click="onShowDrawer"
                     :disabled="(!hasWorkspacePage || !hasSelectedDiagnosis)">
                <template #icon>
                    <NIcon :component="IconSettings" />
                </template>
            </NButton>
        </NButtonGroup>
    </NFlex>
    <NModal @afterLeave="onLeaveModal"
            :mask-closable="false"
            display-directive="if"
            :loading="hasLoading"
            v-model:show="hasShowModal"
            :class="modalClass"
            preset="card"
            :title="modalTitle">
        <NSpace vertical :size="24">
            <DepartmentInputSearch @update:options="onGetSearchResult" />
            <DepartmentListSearchResult v-if="searchedOptions.length > 0"
                                        v-model:selected="departmentActive"
                                        :options="searchedOptions" />
            <DepartmentSelectDepartment v-if="isSmallScreen" v-model:selected="departmentActive"
                                        :options="selectedRegion.children" />
            <NGrid v-else-if="searchedOptions.length === 0" cols="2" x-gap="16">
                <NGi>
                    <NSpace vertical>
                        <NH5 class="!mb-0">
                            Район
                        </NH5>
                        <DepartmentSelectRegion v-model:selected="selectedRegion"
                                                :options="departments" />
                    </NSpace>
                </NGi>
                <NGi v-if="selectedRegion.children !== null">
                    <NSpace vertical>
                        <NH5 class="!mb-0">
                            Медицинская организация
                        </NH5>
                        <DepartmentSelectDepartment v-model:selected="departmentActive"
                                                    :options="selectedRegion.children" />
                    </NSpace>
                </NGi>
            </NGrid>
        </NSpace>
    </NModal>
</template>

<style scoped>

</style>
