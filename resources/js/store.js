import {Characters} from './modules/characters';
import {Locations} from './modules/locations';
import {Messages} from './modules/messages';
import {Notes} from './modules/notes';
import {Quests} from './modules/quests';
import {Roles} from './modules/roles';
import {Users} from './modules/users';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

let store = new Vuex.Store({
    modules: {Characters, Locations, Messages, Notes, Quests, Roles, Users},
    state: {
        user: {}
    },
    mutations: {}
});

export default store;