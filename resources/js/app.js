require('./bootstrap');
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

if (document.getElementById('app')) {
    const vm = new Vue({
        el: '#app',
        router,
        store,
        components: {Messages, Navigation}
    });
}
