import {Campaigns} from './modules/campaigns';
import {Messages} from '../modules/messages';
import {Spells} from "./modules/spells";
import {Users} from "./modules/users";
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

let store = new Vuex.Store({
    modules: {Campaigns, Messages, Spells, Users},
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
        logout({}) {
            axios.post('/logout')
                .then(() => {
                    document.location.href = '/';
                });
        },
    }
});

export default store;