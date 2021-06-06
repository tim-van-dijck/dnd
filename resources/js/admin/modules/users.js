export const Users = {
    namespaced: true,
    state: {
        users: null,
        errors: {}
    },
    actions: {
        previous({commit,state}) {
            if (state.users != null && state.users.meta.current_page > 1) {
                axios.get(`/admin/users?page[number]=${state.users.meta.current_page - 1}`)
                    .then((response) => {
                        commit('SET_USERS', response.data);
                    })
            }
        },
        page({commit,state}, number) {
            if (state.users != null && number > 0 && number <= state.users.meta.last_page)
                axios.get(`/admin/users?page[number]=${number}`)
                    .then((response) => {
                        commit('SET_USERS', response.data);
                    })
        },
        next({commit,state}) {
            if (state.users != null && state.users.meta.current_page < state.users.meta.last_page) {
                axios.get(`/admin/users?page[number]=${state.users.meta.current_page + 1}`)
                    .then((response) => {
                        commit('SET_USERS', response.data);
                    })
            }
        },
        load({commit}, filters) {
            let params = {};
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key];
            }
            let query = new URLSearchParams(params)
            return axios.get(`/admin/users?${query}`)
                .then((response) => {
                    commit('SET_USERS', response.data)
                });
        },

        find({state}, id) {
            if ((state?.users?.data || []).length > 0) {
                let user = state.users.data.find((item) => item.id === id);
                if (user) {
                    return Promise.resolve(user);
                }
            }
            return axios.get(`/admin/users/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        store({dispatch, commit}, data) {
            return axios.post(`/admin/users`, data.user)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'User saved!', {root: true});
                });
        },
        update({dispatch, commit}, data) {
            return axios.put(`/admin/users/${data.id}`, data.user)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'User saved!', {root: true});
                });
        },
        destroy({dispatch}, user) {
            return axios.delete(`/admin/users/${user.id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            dispatch('Messages/success', 'User successfully deleted!', {root: true});
                        });
                })
        }
    },
    mutations: {
        SET_USERS(state, users) {
            state.users = users;
        },
        SET_ERRORS(state, errors) {
            state.errors = errors;
        }
    }
}