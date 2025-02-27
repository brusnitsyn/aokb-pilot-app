<script setup>
import DepartmentSelectRegion from "@/Components/Department/DepartmentSelectRegion.vue"
import DepartmentSelectDepartment from "@/Components/Department/DepartmentSelectDepartment.vue"
import DepartmentInputSearch from "@/Components/Department/DepartmentInputSearch.vue"
import DepartmentListSearchResult from "@/Components/Department/DepartmentListSearchResult.vue"
import {isExtraLargeScreen, isLargeScreen, isMediumScreen, isSmallScreen} from "@/Utils/mediaQuery.js"
import {router, usePage} from "@inertiajs/vue3"
import {IconListSearch, IconMapPin, IconSettings} from '@tabler/icons-vue'
import {Motion} from 'motion-v'

const emits = defineEmits([
    'clickOnShowMoParameters'
])
const departments = ref([])

axios.get('/api/v1/departments').then(res => {
    departments.value = res.data
    console.log(usePage().props.auth.user.department)
    selectedRegion.value = usePage().props.auth.user.department ? {
        key: usePage().props.auth.user.department.region,
        children: departments.value[usePage().props.auth.user.department.region]
    } : {
        key: '',
        children: null,
    }
})
const hasActiveDepartment = computed(() => usePage().props.auth.user.department !== null)
const hasShowModal = defineModel('showModal')
const hasLoading = ref(false)
const selectedRegion = ref({
    key: '',
    children: null,
})
const departmentActive = computed({
    get() {
        return usePage().props.auth.user.department
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
    <NTooltip>
        <template #trigger>
            <Motion
                as-child
                :transition="{ duration: 0.1, }"
                :hover="{ scale: 1.10 }"
                :initial="{ scale: 1 }"
                as="div"
                class="!text-xl !rounded-full !w-[46px] !h-[46px] !px-0">
                <NButton type="primary" secondary bordered round @click="onClickTitle">
                    <NIcon :component="IconListSearch" />
                </NButton>
            </Motion>
        </template>
        Изменить медицинскую организацию
    </NTooltip>

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
