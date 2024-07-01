 
                                                            
import './bootstrap';

import { createApp } from 'vue';
import AppPage from './AppPage.vue';
import '@mdi/font/css/materialdesignicons.css'
import router from './router'
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';


import vuetify from "./vuetify";
createApp(AppPage).use(vuetify).use(router).use(ToastPlugin).mount('#app');
                                                            
                                                        