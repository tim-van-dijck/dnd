import { defineStore } from 'pinia/dist/pinia.esm-browser'

export const useMainStore = defineStore('campaign-main', {
    state: () => (
        {
            campaign: {},
            errors: {},
            languages: null,
            logs: [],
            user: {
                permissions: {},
                roles: []
            }
        }
    ),
    actions: {
        loadCampaign() {
            return axios.get('/api/campaign')
                .then((response) => {
                    this.campaign = response.data
                })
        },
        loadLanguages() {
            return axios.get('/api/languages')
                .then((response) => {
                    this.languages = response.data
                })
        },
        loadLogs() {
            return axios.get('/api/campaign/logs')
                .then((response) => {
                    this.logs = response.data.data
                })
        },
        loadUser() {
            return axios.get('/api/campaign/me')
                .then((response) => {
                    this.user = response.data.data
                })
                .catch((error) => {
                    if ([401, 403, 404].includes(error.response.status)) {
                        window.location = '/'
                    }
                })
        },
        logout() {
            axios.post('/logout')
                .then(() => document.location.href = '/')
        },
        can(permission, entity, id = null) {
            if (!this?.user?.permissions?.hasOwnProperty(entity)) {
                return false
            }
            const permissions = this?.user?.permissions[entity]
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
        admin() {
            return !!this.user.admin
        },
        hasRole(role) {
            return (
                this.user?.roles || []
            )
                .filter(item => item.id === role || item.name === role).length > 0
        }
    }
})