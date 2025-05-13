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
    <NSkeleton v-show="map === null" width="100%" height="100%" class="rounded-3xl overflow-clip border shadow-sm" />
    <YandexMap v-show="map !== null"
               v-model="map"
               cursor-grab
               :settings="settings"
               class="rounded-3xl overflow-clip border shadow-sm">
        <YandexMapDefaultSchemeLayer />
        <YandexMapDefaultFeaturesLayer />

        <YandexMapDefaultMarker v-for="marker in markers" :settings="marker.settings" />
    </YandexMap>
</template>

<style scoped>

</style>
