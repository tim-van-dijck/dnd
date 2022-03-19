require('../bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import router from './router';
import store from './store';
import Campaign from "./components/Campaign";

Vue.use(VueRouter);
Vue.use(Vuex);


window.copy = (item) => JSON.parse(JSON.stringify(item));

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
                    render: (createElement => createElement(Campaign)),
                    router,
                    store
                });
            })
            .catch((error) => {
                if (error.response.status === 403) {
                    document.location.href = '/';
                }
            });
    }
}
