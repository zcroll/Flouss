import './bootstrap';
import '../css/app.css';

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

// Toast options
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
            .use(Toast, toastOptions)  // Add Vue Toastification
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

// document.addEventListener('contextmenu', (e) => e.preventDefault());

// // Disable keyboard shortcuts
// document.addEventListener('keydown', (e) => {
//     // Prevent F12
//     if (e.key === 'F12') {
//         e.preventDefault();
//         return false;
//     }
    
//     // Prevent Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
//     if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) {
//         e.preventDefault(); 
//         return false;
//     }
    
//     // Prevent Ctrl+U (view source)
//     if (e.ctrlKey && e.key === 'u') {
//         e.preventDefault();
//         return false;
//     }
// });


// // Clear console
// setInterval(() => {
//     console.clear();
//     console.log('%c', 'font-size:0;');
// }, 100);

// // Disable debugging
// function debug(e) {
//     e.preventDefault();
//     return false;
// }

// // Catch and prevent various debug attempts
// window.addEventListener('debug', debug);
// window.addEventListener('debugger', debug);
// window.addEventListener('inspect', debug);

// // Additional protection against source viewing
// document.onkeypress = function (event) {
//     event = (event || window.event);
//     if (event.keyCode == 123) {
//         return false;
//     }
// }

// // Disable selecting/copying text
// document.addEventListener('selectstart', (e) => e.preventDefault());
// document.addEventListener('copy', (e) => e.preventDefault());