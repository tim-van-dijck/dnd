export const Users = {
    namespaced: true,
    state: {
        errors: {},
        users: null
    },
    actions: {
        load({commit}) {
            return axios.get('/campaign/users')
                .then((response) => {
                    commit('SET_USERS', response.data);
                });
        },
        find({commit}, id) {
            return axios.get(`/campaign/users/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        invite({commit, dispatch}, user) {
            axios.post('/campaign/users/invite', user)
                .then(() => {
                    dispatch('Messages/success', 'Invite sent!', {root: true});
                })
                .catch((error) => {
                    dispatch('Messages/error', error.response.data.message, {root: true});
                    commit('SET_ERRORS', error.response.data.errors);
                });
        },

        update({commit,dispatch}, data) {
            let payload = data.user;
            payload._method = 'put';
            return axios.post(`/campaign/users/${data.id}`, payload)
                .then(() => {
                    dispatch('Messages/success', 'User successfully saved!', {root: true});
                    commit('SET_ERRORS', {});
                })
                .catch((error) => {
                    dispatch('Messages/error', error.response.data.message, {root: true});
                    commit('SET_ERRORS', error.response.data.errors);
                });
        },
        destroy({commit,dispatch}, user) {
            return axios.delete(`/campaign/users/${user.id}`)
        }
    },
    mutations: {
        SET_ERRORS(state, errors) {
            state.errors = errors;
        },
        SET_USERS(state, users) {
            state.users = users;
        }
    }
};