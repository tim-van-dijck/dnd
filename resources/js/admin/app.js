require('../bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';


Vue.use(VueRouter);
Vue.use(Vuex);

import router from './router';
import store from './store';

import Messages from '../components/partial/messages';
import Navigation from './components/layout/navigation';
import HeaderNavbar from './components/layout/header-navbar';

window.copy = function(item) {
    return JSON.parse(JSON.stringify(item));
}

window.onload = () => {
    const vm = new Vue({
        el: '#admin-app',
        router,
        store,
        components: {HeaderNavbar, Messages, Navigation}
    });
};