require('../bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import router from './router';
import store from './store';
import Admin from './components/Admin'

Vue.use(VueRouter);
Vue.use(Vuex);


window.copy = (item) => JSON.parse(JSON.stringify(item));

window.onload = () => {
    const vm = new Vue({
        el: '#admin-app',
        render: (createElement) => createElement(Admin),
        router,
        store
    });
};