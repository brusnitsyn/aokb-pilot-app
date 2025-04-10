<script setup>
import WorkspaceItem from "@/Components/Workspace/WorkspaceItem.vue";
import {router, usePage} from "@inertiajs/vue3";
import {Motion} from 'motion-v'
import {
    YandexMap, YandexMapControlButton, YandexMapControls,
    YandexMapDefaultFeaturesLayer,
    YandexMapDefaultMarker,
    YandexMapDefaultSchemeLayer, YandexMapEntity, YandexMapGeolocationControl, YandexMapListener, YandexMapMarker
} from "vue-yandex-maps";
import {fetchUserLocation} from "@/Utils/helper.js";

const department = computed(() => usePage().props.auth.user.department)
const hasSetDepartment = computed(() => department.value !== null)

const hasShow = ref(false)
const department_id = ref(null)
const map = shallowRef(null)
const hasAttachedPoint = ref(false)
const centerCoordinates = ref({
    coordinates: []
})
const centerBounds = ref()
const mapSettings = ref({
    location: {
        center: [37.617644, 55.755819],
        zoom: 10
    },
    zoomStrategy: 'zoomToCenter',
    behaviors: [
        'dblClick',
        'drag',
        'scrollZoom',
        'mouseRotate',
        'mouseTilt',
        'magnifier',
        'oneFingerZoom',
        'panTilt',
        'pinchRotate',
        'pinchZoom'
    ],
})
const userLocation = {}
const formRef = ref()
const departmentId = computed(() => {
    if (department.value) {
        department_id.value = department.value.id
        return department.value.id
    }
    return null
})

const comment = ref(null)
const rules = {
    coords: {
        required: true,
        validator: (rule, value) => {
            if (centerCoordinates.value.coordinates.length === 0)
                return new Error('Укажите координаты используя карту')
            return true
        }
    },
    comment: {
        required: true,
        message: 'Заполните поле!',
        validator: () => {
            if (!/\S/.test(comment.value)) {
                return new Error('Поле должно содержать текст');
            }
            return true;
        },
    }
}

const onSubmit = async () => {
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            router.post(route('user.department.update'), {
                id: department_id.value,
                sender_department_id: usePage().props.auth.user.department.id,
                comment: comment.value,
                coords: centerCoordinates.value.coordinates
            }, {
                onSuccess: () => {
                    hasShow.value = false
                    router.visit(route('request.create'))
                }
            })
        }
        else {
            console.log(errors)
            window.$message.error('Произошла ошибка при отправке')
        }
    })
}

const onClickItem = () => {
    if (hasSetDepartment.value === true)
        hasShow.value = true
}

const placeholder = ref('Опишите подробнее о месте эвакуации')
const hasShowAnswers = computed(() => (department_id.value !== 30))

const hasDisabledSubmit = computed(() => {
    if (department_id.value === 30) {
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
}

const onAfterEnter = () => {
    mapSettings.value.location.center = fetchUserLocation()
    userLocation.coordinates = fetchUserLocation()
    centerCoordinates.value.coordinates = fetchUserLocation()
}

const updateCenterMap = (params) => {
    if (!hasAttachedPoint.value) {
        centerCoordinates.value.coordinates = params.location.center
        centerBounds.value = params.location.bounds
        console.log('Новые координаты:', params.location.center)
    }
    console.log('updateCenter:', params)
}

const onAttachPoint = (attach) => {
    hasAttachedPoint.value = attach
    if (attach) {
        mapSettings.value.location.center = [...centerCoordinates.value.coordinates];
        mapSettings.value.behaviors = mapSettings.value.behaviors.filter(x => x !== 'drag')
    } else {
        mapSettings.value.behaviors.push('drag')
    }
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
            @after-leave="onAfterLeave"
            @after-enter="onAfterEnter">
        <template #header>
            <div v-if="department_id === 30" class="!text-base">
                Транспортировка будет осуществлена по координатам
            </div>
            <div v-else class="!text-base">
                Транспортировка будет осуществлена из «{{ department.name }}»
            </div>
        </template>
        <NSpace vertical :size="24">
            <NForm ref="formRef" model="" @submit.prevent="onSubmit">
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
                    <NFormItem label="Точка эвакуации" :rule="rules.coords" :feedback="hasAttachedPoint ? 'Если вы хотите изменить точку, то нажмите на Открепить точку' : 'Укажите местоположение и нажмите на Закрепить точку'">
                        <YandexMap v-model="map"
                                   :cursor-grab="mapSettings.behaviors.includes('drag')"
                                   :settings="mapSettings"
                                   class="rounded-3xl overflow-clip border"
                                   width="100%" height="500px">
                            <YandexMapDefaultSchemeLayer />
                            <YandexMapDefaultFeaturesLayer />

                            <YandexMapDefaultMarker v-if="userLocation" :settings="userLocation" />

                            <!-- Центрированный маркер -->
                            <YandexMapMarker
                                v-if="centerCoordinates.coordinates.length > 0"
                                :settings="centerCoordinates"
                            >
                                <div class="center-marker"></div>
                            </YandexMapMarker>

                            <YandexMapControls :settings="{ position: 'top left' }">
                                <YandexMapEntity>
                                    <div class="coordinates-display">
                                        Ш: {{ centerCoordinates.coordinates[1]?.toFixed(4) }}, Д: {{ centerCoordinates.coordinates[0]?.toFixed(4) }}
                                    </div>
                                </YandexMapEntity>
                            </YandexMapControls>

                            <YandexMapControls :settings="{ position: 'bottom left' }">
                                <YandexMapEntity v-if="!hasAttachedPoint">
                                    <button class="coordinates-display-button" @click="onAttachPoint(true)">
                                        Закрепить точку
                                    </button>
                                </YandexMapEntity>
                                <YandexMapEntity v-else>
                                    <button class="coordinates-display-button" @click="onAttachPoint(false)">
                                         Открепить точку
                                    </button>
                                </YandexMapEntity>
                            </YandexMapControls>

                            <YandexMapControls :settings="{ position: 'left' }">
                                <YandexMapGeolocationControl v-if="!hasAttachedPoint" />
                            </YandexMapControls>

                            <YandexMapListener :settings="{
                                onUpdate: updateCenterMap
                            }" />
                        </YandexMap>
                    </NFormItem>
                    <NFormItemGi label="Комментарий" :rule="rules.comment" class="mt-2">
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
.center-marker {
    width: 20px;
    height: 20px;
    background-color: #EC6608;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(236, 102, 8, 0.5);
    transform: translate(-50%, -50%);
}

.coordinates-display {
    @apply bg-white px-4 py-2 rounded-[12px] z-50 drop-shadow;
}

.coordinates-display-button {
    @apply bg-white px-4 py-2 rounded-[12px] z-50 drop-shadow pointer-events-auto;
}
</style>
