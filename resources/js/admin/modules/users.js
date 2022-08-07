export const Users = {
    state: () => (
        {
            users: null,
            errors: {}
        }
    ),
    actions: {
        previous({ state }) {
            if (state.users != null && state.users.meta.current_page > 1) {
                axios.get(`/api/admin/users?page[number]=${state.users.meta.current_page - 1}`)
                    .then((response) => {
                        state.users = response.data
                    })
            }
        },
        page({ state }, number) {
            if (state.users != null && number > 0 && number <= state.users.meta.last_page) {
                axios.get(`/api/admin/users?page[number]=${number}`)
                    .then((response) => {
                        state.users = response.data
                    })
            }
        },
        next({ state }) {
            if (state.users != null && state.users.meta.current_page < state.users.meta.last_page) {
                axios.get(`/api/admin/users?page[number]=${state.users.meta.current_page + 1}`)
                    .then((response) => {
                        state.users = response.data
                    })
            }
        },
        load({ state }, filters) {
            let params = {}
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key]
            }
            let query = new URLSearchParams(params)
            return axios.get(`/api/admin/users?${query}`)
                .then((response) => {
                    state.users = response.data
                })
        },

        find({ state }, id) {
            if ((
                state?.users?.data || []
            ).length > 0) {
                let user = state.users.data.find((item) => item.id === id)
                if (user) {
                    return Promise.resolve(user)
                }
            }
            return axios.get(`/api/admin/users/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        store({ dispatch, state }, data) {
            return axios.post(`/api/admin/users`, data.user)
                .then(() => {
                    state.errors = {}
                    dispatch('Messages/success', 'User saved!', { root: true })
                })
        },
        update({ dispatch, state }, data) {
            return axios.put(`/api/admin/users/${data.id}`, data.user)
                .then(() => {
                    state.errors = {}
                    dispatch('Messages/success', 'User saved!', { root: true })
                })
        },
        destroy({ dispatch }, user) {
            return axios.delete(`/api/admin/users/${user.id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            dispatch('Messages/success', 'User successfully deleted!', { root: true })
                        })
                })
        }
    }
}