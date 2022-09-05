import { createPinia } from 'pinia';
import './bootstrap';
import { useStyleStore } from '@/Stores/style.js';

import { darkModeKey, styleKey } from '@/config.js';

import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Toaster from '@meforma/vue-toaster';
import 'boxicons/css/boxicons.min.css';
import VueCookies from 'vue3-cookies';

import VueLazyload from 'vue-lazyload';
const errorimage =
    'https://www.naos-marketing.com/wp-content/themes/themify-ultra/themify/img/image-placeholder.webp';
const loadimage = 'https://media3.giphy.com/media/3oEjI6SIIHBdRxXI40/200.gif';

const appName =
    window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        let page = '';
        if (name.includes('@')) {
            const matched = /@(.*)::/.exec(name);
            const module = matched[1];
            const pageName = name.replace(matched[0], '');
            page = resolvePageComponent(
                `../../Modules/${module}/Resources/views/${pageName}.vue`,
                import.meta.glob('../../Modules/**/**/**/**/*.vue')
            );
        } else {
            page = resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue')
            );
        }
        page.then((module) => {
            module.default.layout = module.default.layout;
        });
        return page;
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(Toaster)
            .use(VueCookies, {
                expireTimes: '30d',
                path: '/',
                domain: '',
                secure: true,
                sameSite: 'None',
            })
            .use(VueLazyload, {
                error: errorimage,
                loading: loadimage,
            })
            .use(pinia)
            .use(ZiggyVue, Ziggy)
            .component('Link', Link)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });

const styleStore = useStyleStore(pinia);

/* App style */
styleStore.setStyle(localStorage[styleKey] ?? 'basic');

/* Dark mode */
if (
    (!localStorage[darkModeKey] &&
        window.matchMedia('(prefers-color-scheme: dark)').matches) ||
    localStorage[darkModeKey] === '1'
) {
    styleStore.setDarkMode(true);
}
