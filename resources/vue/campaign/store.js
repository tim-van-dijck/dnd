import { createStore } from 'vuex'
import { Messages } from '../modules/messages'
import { Characters } from './modules/characters'
import { Inventory } from './modules/inventory'
import { Journal } from './modules/journal'
import { Locations } from './modules/locations'
import { Notes } from './modules/notes'
import { Permissions } from './modules/permissions'
import { Quests } from './modules/quests'
import { Roles } from './modules/roles'
import { Spells } from './modules/spells'
import { Users } from './modules/users'

export const store = createStore({
    modules: { Characters, Inventory, Journal, Locations, Messages, Notes, Permissions, Quests, Roles, Spells, Users },
    state: {
        campaign: {},
        errors: {},
        languages: null,
        logs: [],
        user: {
            permissions: {},
            roles: []
        }
    },
    actions: {
        loadCampaign({ state }) {
            return axios.get('/api/campaign')
                .then((response) => {
                    state.campaign = response.data
                })
        },
        loadLanguages({ state }) {
            return axios.get('/api/languages')
                .then((response) => {
                    state.languages = response.data
                })
        },
        loadLogs({ state }) {
            return axios.get('/api/campaign/logs')
                .then((response) => {
                    state.logs = response.data.data
                })
        },
        loadUser({ state }) {
            return axios.get('/api/campaign/me')
                .then((response) => {
                    state.user = response.data
                })
                .catch((error) => {
                    if ([401, 403, 404].includes(error.response.status)) {
                        window.location = '/'
                    }
                })
        },
        logout({}) {
            axios.post('/logout')
                .then(() => {
                    document.location.href = '/'
                })
        }
    },
    getters: {
        can: state => (permission, entity, id = null) => {
            if (!state.user.permissions.hasOwnProperty(entity)) {
                return false
            }
            const permissions = state.user.permissions[entity]
            if (permissions[permission]) {
                return true
            }
            if (permissions.exceptions) {
                if (id > 0) {
                    return permissions.exceptions.hasOwnProperty(id) && permissions.exceptions[id][permission]
                } else {
                    return Object.keys(permissions.exceptions).length > 0
                }
            }
            return false
        },
        admin: state => state.user.admin,
        hasRole: state => (role) => {
            return (
                state?.user?.roles || []
            ).filter(item => item.id === role || item.name === role).length > 0
        }
    }
})

export default store