/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import {createApp} from 'vue'
import store from './store/store.js';
require('./bootstrap')
import App from './layouts/App.vue'
import axios from 'axios'
import router from './router'

const app = createApp(App)
axios.defaults.withCredentials = true;
axios.defaults.headers = { 'Content-Type': 'application/json' };
axios.defaults.headers = { 'Accept': 'application/json' };
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
app.config.globalProperties.$axios = axios;
app.use(router)
app.use(store)
app.mount('#app')