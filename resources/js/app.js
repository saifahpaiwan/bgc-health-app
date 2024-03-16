import './bootstrap';
import '../css/root-color.css';
import '../css/bootstrap.css'; 
import '../css/app-bg.css';
import '../css/icons.css';
import 'primevue/resources/themes/aura-light-blue/theme.css';    
  
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'; 
import PrimeVue from 'primevue/config'; 
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, { ripple: true  }) 
            .mount(el);
    },
    progress: {
        delay: 250,
        color: '#f60000',  
        includeCSS: true, 
        showSpinner: false,
    },
});
