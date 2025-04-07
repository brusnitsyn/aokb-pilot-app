import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import {mask} from "vue-the-mask";
import { createYmaps } from "vue-yandex-maps";
// import { MotionPlugin } from '@vueuse/motion'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(createYmaps({
                apikey: import.meta.env.VITE_YM_API_KEY
            }))
            .directive('mask', mask)
            // .use(MotionPlugin)
            .mount(el);
    },
    progress: {
        color: '#EC6608',
    },
});
