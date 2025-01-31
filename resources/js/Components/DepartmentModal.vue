<script setup>
import DepartmentSelectRegion from "@/Components/DepartmentSelectRegion.vue";
import DepartmentSelectDepartment from "@/Components/DepartmentSelectDepartment.vue";
import {useCookies} from "@vueuse/integrations/useCookies";

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
const selectedRegion = ref({
    key: '',
    children: null,
})

const departmentActive = computed({
    get() {
        return departmentCookie.get('activeDepartment')
    },
    async set(value) {
        await axios.post('/api/v1/user/department', {
            ...value
        }).then(() => {
            departmentCookie.set('activeDepartment', value)
            hasShowModal.value = false
        })
    }
})

function onClickTitle() {
    hasShowModal.value = true
}
</script>

<template>
    <NH2 class="!my-0 cursor-pointer" @click="onClickTitle">
        {{ departmentActive ? departmentActive.name : 'Выберите МО' }}
    </NH2>
    <NModal display-directive="if" v-model:show="hasShowModal" class="max-w-screen-md h-[535px]" preset="card" title="Выберите медицинскую организацию">
        <NSpace vertical>
            <NGrid cols="2" x-gap="16">
                <NGi>
                    <NSpace vertical>
                        <NH5 class="!mb-0">
                            Район
                        </NH5>
                        <DepartmentSelectRegion v-model:selected="selectedRegion" :options="departments" />
                    </NSpace>
                </NGi>
                <NGi v-if="selectedRegion.children !== null">
                    <NSpace vertical>
                        <NH5 class="!mb-0">
                            Медицинская организация
                        </NH5>
                        <DepartmentSelectDepartment v-model:selected="departmentActive" :options="selectedRegion.children" />
                    </NSpace>
                </NGi>
            </NGrid>
        </NSpace>
    </NModal>
</template>

<style scoped>

</style>
