<script setup>
// markers
import {
    YandexMap,
    YandexMapDefaultFeaturesLayer,
    YandexMapDefaultMarker,
    YandexMapDefaultSchemeLayer
} from "vue-yandex-maps";

const props = defineProps({
    markers: Array,
    settings: Object
})

const map = shallowRef()
</script>

<template>
    <NSpin :show="map === null" class="h-full" content-class="h-full">
        <YandexMap v-model="map"
                   cursor-grab
                   :settings="settings"
                   class="rounded-3xl overflow-clip border shadow-sm">
            <YandexMapDefaultSchemeLayer />
            <YandexMapDefaultFeaturesLayer />

            <YandexMapDefaultMarker v-for="marker in markers" :settings="marker.settings" />
        </YandexMap>
    </NSpin>
</template>

<style scoped>

</style>
