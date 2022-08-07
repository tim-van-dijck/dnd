export const Roles = {
    namespaced: true,
    state: {
        roles: null,
        permissions: null
    },
    actions: {
        load({ state }) {
            axios.get('/api/campaign/roles')
                .then((response) => {
                    state.roles = response.data
                })
        },
        loadPermissions({ state }) {
            return axios.get('/api/permissions')
                .then((response) => {
                    state.permissions = response.data
                })
        },
        find({}, id) {
            return axios.get(`/api/campaign/roles/${id}`)
                .then((response) => {
                    return response.data.data
                })
        },
        store({ dispatch }, role) {
            return axios.post('/api/campaign/roles', role)
                .then(() => {
                    dispatch('Messages/success', 'Role successfully saved!', { root: true })
                })
                .catch((error) => {
                    dispatch('Messages/error', error.response.data.message, { root: true })
                })
        },
        update({ dispatch }, { id, role }) {
            const payload = { ...role, _method: 'put' }
            return axios.post(`/api/campaign/roles/${id}`, payload)
                .then(() => {
                    dispatch('Messages/success', 'Role successfully saved!', { root: true })
                })
                .catch((error) => {
                    dispatch('Messages/error', error.response.data.message, { root: true })
                })
        },
        destroy({ dispatch }, role) {
            return axios.delete(`/api/campaign/roles/${role.id}`)
                .then(() => {
                    dispatch('Messages/success', 'Role successfully deleted!', { root: true })
                })
                .catch((error) => {
                    dispatch('Messages/error', error.response.data.message, { root: true })
                })
        }
    },
    mutations: {}
}