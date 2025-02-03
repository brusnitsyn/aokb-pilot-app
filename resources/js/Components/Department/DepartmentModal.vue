<script setup>
import DepartmentSelectRegion from "@/Components/Department/DepartmentSelectRegion.vue";
import DepartmentSelectDepartment from "@/Components/Department/DepartmentSelectDepartment.vue";
import {useCookies} from "@vueuse/integrations/useCookies";
import DepartmentInputSearch from "@/Components/Department/DepartmentInputSearch.vue";
import DepartmentListSearchResult from "@/Components/Department/DepartmentListSearchResult.vue";
import {isExtraLargeScreen, isLargeScreen, isMediumScreen, isSmallScreen} from "@/Utils/mediaQuery.js";
import DepartmentList from "@/Components/Department/DepartmentList.vue";

const departments = ref([])

axios.get('/api/v1/departments').then(res => {
    departments.value = res.data
    selectedRegion.value = departmentCookie.get('activeDepartment') ? {
        key: departmentCookie.get('activeDepartment').region,
        children: departments.value[departmentCookie.get('activeDepartment').region]
    } : {
        key: '',
        children: null,
    }
})

const departmentCookie = useCookies(['activeDepartment'])
const hasShowModal = ref(false)
const hasLoading = ref(false)
const selectedRegion = ref({
    key: '',
    children: null,
})
const departmentActive = computed({
    get() {
        return departmentCookie.get('activeDepartment')
    },
    async set(value) {
        hasLoading.value = true
        await axios.post('/api/v1/user/department', {
            ...value
        }).then(() => {
            departmentCookie.set('activeDepartment', value)
            hasShowModal.value = false
        }).finally(() => {
            hasLoading.value = false
        })
    }
})
const searchedOptions = ref([])

const modalClass = computed(() => {
    return [
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

const departmentActiveClass = computed(() => {
    return [
        isSmallScreen.value ? '!text-base !leading-5' : '',
        isMediumScreen.value ? '!text-lg' : '',
        isLargeScreen.value
        || isExtraLargeScreen.value ? '!text-lg' : ''
    ]
})


function onClickTitle() {
    hasShowModal.value = true
}

function onGetSearchResult(options) {
    searchedOptions.value = options
}

function onLeaveModal() {
    searchedOptions.value = []
}
</script>

<template>
    <NH2 class="!my-0 cursor-pointer hover:text-green-500" :class="departmentActiveClass" @click="onClickTitle">
        {{ departmentActive ? departmentActive.name : 'Выберите МО' }}
    </NH2>
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
