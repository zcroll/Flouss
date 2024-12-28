import './bootstrap';
import '../css/app.css';
import '../css/toast.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import draggable from 'vuedraggable';
import __ from './lang';
import { createPinia } from 'pinia';

// Import Vue Toastification
import Toast from "vue-toastification";
// Import the CSS
import "vue-toastification/dist/index.css";

import './pan-init'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const toastOptions = {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, toastOptions)
            .component('draggable', draggable)

        app.config.globalProperties.__ = __;

        const pinia = createPinia()
        app.use(pinia)

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

