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
        user: {
            permissions: {}
        }
    },
    actions: {
        loadUser({commit}) {
            return axios.get('/campaign/me')
                .then((response) => {
                    commit('SET_USER', response.data);
                })
        }
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        }
    },
    getters: {
        can: state => (permission, entity, id = null) => {
            if (!state.user.permissions.hasOwnProperty(entity)) {
                return false;
            }
            let permissions = state.user.permissions[entity];
            if (permissions[permission]) {
                return true;
            }
            if (permissions.exceptions) {
                if (id > 0) {
                    return permissions.exceptions.hasOwnProperty(id) && permissions.exceptions[id][permission];
                } else {
                    return Object.keys(permissions.exceptions).length > 0;
                }
            }
            return false;
        }
    }
});

export default store;