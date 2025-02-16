<script setup>
import {IconLock} from "@tabler/icons-vue"
import {Link} from '@inertiajs/vue3'
import { Motion } from 'motion-v'
const props = defineProps({
    href: {
        type: String,
    },
    header: {
        type: String
    },
    icon: {

    },
    type: {
        type: String,
        default: 'default'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    disabledReason: {
        type: String
    },
    imageUrl: String
})
</script>

<template>
    <div v-if="disabled" class="relative border transition-colors overflow-clip rounded-3xl bg-white drop-shadow-sm">
        <div class="p-4 px-5 relative bg-no-repeat bg-right-bottom h-[140px] overflow-clip">
            <slot v-if="$slots.header" name="header" />
            <div v-else class="w-2/3 text-base font-semibold leading-5 select-none">
                {{ header }}
            </div>
            <div class="w-[140px] h-[120px] absolute -right-6 -bottom-6 select-none bg-no-repeat bg-cover" :style="`background-image: url(${imageUrl})`" />
        </div>
        <div class="absolute inset-0 bg-black bg-opacity-10 backdrop-blur-[4px] rounded-[3px]">
            <div class="flex items-start justify-center h-full w-full">
                <div class="p-4 px-5 flex flex-col gap-y-2 items-start w-full">
                    <div class="rounded-full bg-[#EC6608] bg-opacity-80 h-10 w-10 flex items-center justify-center">
                        <NIcon :component="IconLock" size="24" />
                    </div>
                    <NText strong class="select-none max-w-[160px] leading-5">
                        {{ disabledReason }}
                    </NText>
                </div>
            </div>
        </div>
    </div>
    <Motion v-else
            :transition="{ duration: 0.1, }"
            :hover="{ scale: 1.05 }"
            :initial="{ scale: 1 }"
            as="div"
            class="relative border transition-colors overflow-clip rounded-3xl bg-white drop-shadow-sm"
            :class="{'hover:border-[#EC6608] cursor-pointer': !disabled}">
        <Link v-if="href" :href="href">
            <div class="p-4 px-5 relative bg-no-repeat bg-right-bottom h-[140px] overflow-clip">
                <slot v-if="$slots.header" name="header" />
                <div v-else class="w-2/3 text-base font-semibold leading-5 select-none">
                    {{ header }}
                </div>
                <div class="w-[140px] h-[120px] absolute -right-6 -bottom-6 select-none bg-no-repeat bg-cover" :style="`background-image: url(${imageUrl})`" />
            </div>
        </Link>
        <div v-else class="p-4 px-5 relative bg-no-repeat bg-right-bottom h-[140px] overflow-clip">
            <slot v-if="$slots.header" name="header" />
            <div v-else class="w-2/3 text-base font-semibold leading-5 select-none">
                {{ header }}
            </div>
            <div class="w-[140px] h-[120px] absolute -right-6 -bottom-6 select-none bg-no-repeat bg-cover" :style="`background-image: url(${imageUrl})`" />
        </div>
    </Motion>
</template>

<style scoped>

</style>
