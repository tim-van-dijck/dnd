require('../bootstrap');

import Icons from 'uikit/dist/js/uikit-icons';
import UIkit from 'uikit';
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

UIkit.use(Icons);

Vue.use(VueRouter);
Vue.use(Vuex);

import router from './router';
import store from './store';

import Messages from './components/layout/messages';
import Navigation from './components/layout/navigation';
import HeaderNavbar from './components/layout/header-navbar';

window.copy = function(item) {
    return JSON.parse(JSON.stringify(item));
}

window.onload = () => {
    if (document.getElementById('app')) {
        store.dispatch('loadCampaign')
            .catch((error) => {
                if (error.response.status === 403) {
                    document.location.href = '/';
                }
            });
        store.dispatch('loadUser')
            .then(() => {
                const vm = new Vue({
                    el: '#app',
                    router,
                    store,
                    components: {HeaderNavbar, Messages, Navigation}
                });
            })
            .catch((error) => {
                if (error.response.status === 403) {
                    document.location.href = '/';
                }
            });
    }
}
