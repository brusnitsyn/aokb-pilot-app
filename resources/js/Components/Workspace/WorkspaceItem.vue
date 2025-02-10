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

const headerClass = computed(() => {
    const classes = []
    switch (props.type) {
        case 'default':
            classes.push('text-green-600')
        break
        case 'error':
            classes.push('text-red-600')
        break
    }

    return classes
})

const iconClass = computed(() => {
    const classes = []
    switch (props.type) {
        case 'default':
            classes.push('text-green-600')
            break
        case 'error':
            classes.push('text-red-600')
            break
    }

    return classes
})


</script>

<template>
    <Motion :hover="{ scale: 1.05 }" class="relative border transition-colors overflow-clip rounded-3xl bg-white drop-shadow-sm" :class="{'hover:border-[#EC6608] cursor-pointer': !disabled}">
        <Link v-if="href" :href="href">
            <div class="p-4 px-5 relative bg-no-repeat bg-right-bottom h-[140px] overflow-clip">
                <slot v-if="$slots.header" name="header" />
                <div v-else class="w-[160px] text-base font-semibold leading-6 select-none">
                    {{ header }}
                </div>
                <div class="w-[140px] h-[120px] absolute -right-6 -bottom-6 select-none bg-no-repeat bg-cover" :style="`background-image: url(${imageUrl})`" />
            </div>
        </Link>
        <div v-else class="p-4 px-5 relative bg-no-repeat bg-right-bottom h-[140px] overflow-clip">
            <slot v-if="$slots.header" name="header" />
            <div v-else class="w-[160px] text-base font-semibold leading-5 select-none">
                {{ header }}
            </div>
            <div class="w-[140px] h-[120px] absolute -right-6 -bottom-6 select-none bg-no-repeat bg-cover" :style="`background-image: url(${imageUrl})`" />
        </div>
        <div v-if="disabled" class="absolute inset-0 bg-black bg-opacity-10 backdrop-blur-[4px] rounded-[3px]">
            <div class="flex items-center justify-center h-full">
                <NSpace vertical :size="0" item-class="flex" align="center" inline justify="center">
                    <NIcon :component="IconLock" size="28" />
                    <NText strong class="select-none">
                      {{ disabledReason }}
                    </NText>
                </NSpace>
            </div>
        </div>
    </Motion>
</template>

<style scoped>

</style>
