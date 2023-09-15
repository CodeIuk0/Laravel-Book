
import { createApp } from 'vue';

import App from './components/App.vue';

import ElementPlus from 'element-plus'

import store from "./sotre/sotre"

import router from './router'

const app = createApp(App,{
    router
  });

app.use(router);
app
app.use(ElementPlus);
app.use(store);

app.mount('#app');
