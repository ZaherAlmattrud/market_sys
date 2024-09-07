

import './bootstrap';

import { createApp } from 'vue';
import AppPage from './AppPage.vue';
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/dist/vuetify.min.css';
import router from './router'
import store from './vuex'
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

import vuetify from "./vuetify";
 

 
createApp(AppPage).use(store).use(vuetify).use(router).use(ToastPlugin).mount('#app');

