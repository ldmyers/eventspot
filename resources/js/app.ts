import './bootstrap';
import '../css/app.css';
import {createApp, h, DefineComponent} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import {createRouter, createWebHistory} from 'vue-router';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/events/:categorySlug?/:categoryId?',
            name: 'events',
            component: () => import('./Pages/Event/Index.vue'),
            props: true
        }
    ]
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(router) // Use the router instance here
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4b5563',
    },
});

