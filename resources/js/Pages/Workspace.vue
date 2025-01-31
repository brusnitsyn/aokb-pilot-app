<script setup>
import {Link} from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue';
import { IconCirclePlus, IconTableDashed, IconDoorExit } from '@tabler/icons-vue'
</script>

<template>
    <AppLayout title="Dashboard">
        <NFlex vertical :size="20" class="max-w-xl mx-auto h-full" justify="space-between">
            <Link :href="route('request.create')">
                <NCard hoverable class="cursor-pointer">
                    <template  #header>
                        <div class="text-[#18A058]">
                            Создать запрос на транспортировку
                        </div>
                    </template>
                    <template #header-extra>
                        <NIcon :component="IconCirclePlus" size="28" class="text-[#18A058]" />
                    </template>
                </NCard>
            </Link>
            <NCard hoverable class="cursor-pointer">
                <template  #header>
                    <div class="text-[#18A058]">
                        Мои запросы
                    </div>
                </template>
                <template #header-extra>
                    <NIcon :component="IconTableDashed" size="28" class="text-[#18A058]" />
                </template>
            </NCard>
            <NCard hoverable class="cursor-pointer">
                <template  #header>
                    <div class="text-[#d03050]">
                        Выход
                    </div>
                </template>
                <template #header-extra>
                    <NIcon :component="IconDoorExit" size="28" class="text-[#d03050]" />
                </template>
            </NCard>
        </NFlex>
    </AppLayout>
</template>
