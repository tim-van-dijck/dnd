import Vue from 'vue';

export const Roles = {
    namespaced: true,
    state: {
        roles: null,
        permissions: null,
        errors: {}
    },
    actions: {
        load({commit}) {
            axios.get('/campaign/roles')
                .then((response) => {
                    commit('SET_ROLES', response.data);
                });
        },
        loadPermissions({commit}) {
            return axios.get('/permissions')
                .then((response) => {
                    commit('SET_PERMISSIONS', response.data);
                })
        },
        find({}, id) {
            return axios.get(`/campaign/roles/${id}`)
                .then((response) => {
                    return response.data.data;
                });
        },
        store({commit, dispatch}, role) {
            return axios.post('/campaign/roles', role)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Role successfully saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        update({commit, dispatch}, data) {
            let payload = data.role;
            payload._method = 'put';
            return axios.post(`/campaign/roles/${data.id}`, payload)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Role successfully saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        destroy({dispatch}, role) {
            return axios.delete(`/campaign/roles/${role.id}`)
                .then(() => {
                    dispatch('Messages/success', 'Role successfully deleted!', {root: true});
                });
        }
    },
    mutations: {
        SET_ERRORS(state, errors) {
            state.errors = errors;
        },
        SET_PERMISSIONS(state, permissions) {
            Vue.set(state, 'permissions', permissions);
        },
        SET_ROLES(state, roles) {
            state.roles = roles;
        }
    }
};