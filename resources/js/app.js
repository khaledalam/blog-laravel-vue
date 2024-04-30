import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler.js';
import ToastPlugin from 'vue-toast-notification';
// Import one of the available themes
// import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-bootstrap.css';

import router from './router';
import LoginPage from './pages/LoginPage.vue';


createApp({
    components: {
        LoginPage,
    }
})
    .use(router)
    .use(ToastPlugin)
    .mount('#app');
