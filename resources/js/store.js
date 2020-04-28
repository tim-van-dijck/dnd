import {Characters} from './modules/characters';
import {Locations} from './modules/locations';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

let store = new Vuex.Store({
    modules: {Characters, Locations},
    state: {
        user: {},
        trans: {}
    },
    mutations: {}
});

export default store;