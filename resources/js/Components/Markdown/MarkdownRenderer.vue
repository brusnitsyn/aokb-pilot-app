<script setup>
import { computed } from 'vue';
import markdownit from 'markdown-it';

const props = defineProps({
    content: {
        type: String,
        required: true
    }
})

const md = markdownit({
    html: true, // Разрешить HTML-теги в Markdown
    linkify: true, // Автоматически преобразовывать URL в ссылки
    typographer: true, // Красивые типографские замены
    breaks: true
})

const htmlContent = computed(() => md.render(props.content))
</script>

<template>
    <NEl class="markdown-content" v-html="htmlContent" />
</template>

<style lang="scss">
.markdown-content {
    h1, h2, h3, h4 {
        color: var(--primary-color);
        position: relative;
    }

    h1 {
        margin-bottom: 0.8em;
    }

    h2 {
        margin-bottom: 0.2em;
    }

    h3 {
        margin-bottom: 0.2em;
    }

    hr {
        display: none;
    }

    ul, ol {
        padding-left: 0.5rem;
        margin-bottom: 0.5rem;

        li {
            margin-bottom: 0.2rem;
            position: relative;
        }
    }

    strong {
        color: var(--primary-color)
    }
}
</style>
