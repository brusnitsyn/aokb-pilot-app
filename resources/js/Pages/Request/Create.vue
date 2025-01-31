<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { IconArrowLeft, IconArrowRight, IconHome } from "@tabler/icons-vue";
import {Link} from "@inertiajs/vue3";
const carousel = ref()
const checkboxGroupValue = ref(null)
const checkboxGroupValue2 = ref(null)
const checkboxGroupValue3 = ref(null)
function nextSlide() {
    if (checkboxGroupValue.value === '1') {
        toSlide(2)
    } else {
        toSlide(1)
    }
}
function nextSlide2() {
    carousel?.value.next()
}
function nextSlide3() {
    carousel?.value.next()
}
function prevSlide() {
    carousel?.value.prev()
}
function prevSlide2() {
    carousel?.value.prev()
}
function prevSlide3() {
    if (checkboxGroupValue.value === '1') {
        toSlide(0)
    } else {
        carousel?.value.prev()
    }
}
function toSlide(index) {
    carousel?.value.to(index)
}
</script>
<template>
    <AppLayout>
        <NSpace vertical size="large" class="max-w-4xl mx-auto h-full">
            <Link :href="route('workspace')" class="h-full">
                <NButton secondary>
                    <template #icon>
                        <NIcon :component="IconHome" />
                    </template>
                    Домой
                </NButton>
            </Link>
            <NCarousel
                ref="carousel"
                :touchable="false"
                :loop="false"
                style="width: 100%; height: 100%"
            >
                <NCard>
                    <template #header>
                        <div>
                            1. Возможность эвакуации собственными силами
                        </div>
                    </template>
                    <NRadioGroup v-model:value="checkboxGroupValue">
                        <NSpace vertical>
                            <NRadio value="1">
                                Имеется
                            </NRadio>
                            <NRadio value="2">
                                Отсутствует
                            </NRadio>
                        </NSpace>
                    </NRadioGroup>
                    <template #action>
                        <NGrid cols="2">
                            <NGi>
                            </NGi>
                            <NGi class="flex justify-end">
                                <NButton type="primary" secondary icon-placement="right" :disabled="checkboxGroupValue === null" @click="nextSlide">
                                    <template #icon>
                                        <NIcon :component="IconArrowRight" />
                                    </template>
                                    Следующий вопрос
                                </NButton>
                            </NGi>
                        </NGrid>
                    </template>
                </NCard>
                <NCard>
                    <template #header>
                        <div>
                            Возможность посадки вертолета
                        </div>
                    </template>
                    <NRadioGroup v-model:value="checkboxGroupValue2">
                        <NSpace vertical>
                            <NRadio value="1">
                                Имеется
                            </NRadio>
                            <NRadio value="2">
                                Затруднительна
                            </NRadio>
                            <NRadio value="3">
                                Отсутствует
                            </NRadio>
                        </NSpace>
                    </NRadioGroup>
                    <template #action>
                        <NGrid cols="2">
                            <NGi>
                                <NButton secondary @click="prevSlide">
                                    <template #icon>
                                        <NIcon :component="IconArrowLeft" />
                                    </template>
                                    Предыдущий вопрос
                                </NButton>
                            </NGi>
                            <NGi class="flex justify-end">
                                <NButton type="primary" secondary icon-placement="right" :disabled="checkboxGroupValue2 === null" @click="nextSlide2">
                                    <template #icon>
                                        <NIcon :component="IconArrowRight" />
                                    </template>
                                    Следующий вопрос
                                </NButton>
                            </NGi>
                        </NGrid>
                    </template>
                </NCard>
                <NCard>
                    <template #header>
                        <div>
                            2. Труднодоступность
                        </div>
                    </template>
                    <NRadioGroup v-model:value="checkboxGroupValue3">
                        <NSpace vertical>
                            <NRadio value="1">
                                Да
                            </NRadio>
                            <NRadio value="2">
                                Нет
                            </NRadio>
                        </NSpace>
                    </NRadioGroup>
                    <template #action>
                        <NGrid cols="2" >
                            <NGi>
                                <NButton secondary @click="prevSlide3">
                                    <template #icon>
                                        <NIcon :component="IconArrowLeft" />
                                    </template>
                                    Предыдущий вопрос
                                </NButton>
                            </NGi>
                            <NGi>
                            </NGi>
                        </NGrid>
                    </template>
                </NCard>
            </NCarousel>
        </NSpace>
    </AppLayout>
</template>
<style scoped>
</style>
