export const Spells = {
    namespaced: true,
    state: {
        spells: null,
        errors: {}
    },
    actions: {
        previous({commit,state}) {
            if (state.spells != null && state.spells.meta.current_page > 1) {
                axios.get(`/admin/spells?page[number]=${state.spells.meta.current_page - 1}`)
                    .then((response) => {
                        commit('SET_SPELLS', response.data);
                    })
            }
        },
        page({commit,state}, number) {
            if (state.spells != null && number > 0 && number <= state.spells.meta.last_page)
                axios.get(`/admin/spells?page[number]=${number}`)
                    .then((response) => {
                        commit('SET_SPELLS', response.data);
                    })
        },
        next({commit,state}) {
            if (state.spells != null && state.spells.meta.current_page < state.spells.meta.last_page) {
                axios.get(`/admin/spells?page[number]=${state.spells.meta.current_page + 1}`)
                    .then((response) => {
                        commit('SET_SPELLS', response.data);
                    })
            }
        },
        load({commit}, filters) {
            let params = {};
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key];
            }
            let query = new URLSearchParams(params)
            return axios.get(`/admin/spells?${query}`)
                .then((response) => {
                    commit('SET_SPELLS', response.data)
                });
        },

        find({state}, id) {
            if ((state?.spells?.data || []).length > 0) {
                let spell = state.spells.data.find((item) => item.id === id);
                if (spell) {
                    return Promise.resolve(spell);
                }
            }
            return axios.get(`/admin/spells/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        store({dispatch, commit}, data) {
            return axios.post(`/admin/spells`, data.spell)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Spell saved!', {root: true});
                });
        },
        update({dispatch, commit}, data) {
            return axios.put(`/admin/spells/${data.id}`, data.spell)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Spell saved!', {root: true});
                });
        },
        destroy({dispatch}, spell) {
            return axios.delete(`/admin/spells/${spell.id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            dispatch('Messages/success', 'Spell successfully deleted!', {root: true});
                        });
                })
        }
    },
    mutations: {
        SET_SPELLS(state, spells) {
            state.spells = spells;
        },
        SET_ERRORS(state, errors) {
            state.errors = errors;
        }
    }
}