 
                                                            
import './bootstrap';

import { createApp } from 'vue';
import AppPage from './AppPage.vue';
import '@mdi/font/css/materialdesignicons.css'
import router from './router'


import vuetify from "./vuetify";
createApp(AppPage).use(vuetify).use(router).mount('#app');
                                                            
                                                        