<script setup>
import {IconLock} from "@tabler/icons-vue"
import {Link} from '@inertiajs/vue3'
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
    }
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
    <div class="relative overflow-clip">
        <Link v-if="href" :href="href">
            <NCard hoverable class="cursor-pointer">
                <template #header>
                    <div v-bind:class="headerClass">
                        {{ header }}
                    </div>
                </template>
                <template #header-extra>
                    <NIcon :component="icon" size="28" :class="iconClass" />
                </template>
            </NCard>
        </Link>
        <NCard v-else hoverable class="cursor-pointer">
            <template #header>
                <div v-bind:class="headerClass">
                    {{ header }}
                </div>
            </template>
            <template #header-extra>
                <NIcon :component="icon" size="28" :class="iconClass" />
            </template>
        </NCard>
        <div v-if="disabled" class="absolute inset-0 bg-black bg-opacity-10 backdrop-blur-[4px] rounded-[3px]">
            <div class="flex items-center justify-center h-full">
                <NSpace vertical :size="0" item-class="flex" align="center" inline justify="center">
                    <NIcon :component="IconLock" size="28" />
                    <NText strong>
                      {{ disabledReason }}
                    </NText>
                </NSpace>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
