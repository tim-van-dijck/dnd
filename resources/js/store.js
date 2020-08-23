import {Characters} from './modules/characters';
import {Locations} from './modules/locations';
import {Messages} from './modules/messages';
import {Notes} from './modules/notes';
import {Permissions} from './modules/permissions';
import {Quests} from './modules/quests';
import {Roles} from './modules/roles';
import {Users} from './modules/users';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

let store = new Vuex.Store({
    modules: {Characters, Locations, Messages, Notes, Permissions, Quests, Roles, Users},
    state: {
        campaign: {},
        errors: {},
        languages: null,
        logs: [],
        user: {
            permissions: {}
        }
    },
    actions: {
        loadCampaign({commit}) {
            return axios.get('/campaign')
                .then((response) => {
                    commit('SET_CAMPAIGN', response.data);
                });
        },
        loadLanguages({commit}) {
            return axios.get('/languages')
                .then((response) => {
                    commit('SET_LANGUAGES', response.data);
                })
        },
        loadLogs({commit}) {
            return axios.get('/campaign/logs')
                .then((response) => {
                    commit('SET_LOGS', response.data.data);
                })
        },
        loadUser({commit}) {
            return axios.get('/campaign/me')
                .then((response) => {
                    commit('SET_USER', response.data);
                })
                .catch((error) => {
                    if ([401,403,404].includes(error.response.status)) {
                        window.location = '/';
                    }
                });
        }
    },
    mutations: {
        SET_CAMPAIGN(state, campaign) {
            state.campaign = campaign;
        },
        SET_LANGUAGES(state, languages) {
            state.languages = languages
        },
        SET_LOGS(state, logs) {
            state.logs = logs;
        },
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