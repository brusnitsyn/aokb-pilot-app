<script setup>
import AppLogoShort from "@/Components/App/AppLogoShort.vue";
import AppLogoFull from "@/Components/App/AppLogoFull.vue";
import {isSmallScreen} from "@/Utils/mediaQuery.js";
</script>

<template>
    <AppLogoShort v-if="isSmallScreen" />
    <AppLogoFull v-else />
</template>

<style scoped>

</style>
