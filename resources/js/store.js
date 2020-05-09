import {Characters} from './modules/characters';
import {Locations} from './modules/locations';
import {Messages} from './modules/messages';
import {Quests} from './modules/quests';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

let store = new Vuex.Store({
    modules: {Characters, Locations, Messages, Quests},
    state: {
        user: {}
    },
    mutations: {}
});

export default store;