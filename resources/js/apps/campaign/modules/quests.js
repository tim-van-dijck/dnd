export const Quests = {
    namespaced: true,
    state: {
        errors: {},
        quests: null
    },
    actions: {
        previous({ commit, state }) {
            if (state.quests != null && state.quests.meta.current_page > 1) {
                axios.get(`/api/campaign/quests?page[number]=${state.quests.meta.current_page - 1}`)
                    .then((response) => {
                        commit('SET_QUESTS', response.data)
                    })
            }
        },
        page({ commit, state }, number) {
            if (state.quests != null && number > 0 && number <= state.quests.meta.last_page) {
                axios.get(`/api/campaign/quests?page[number]=${number}`)
                    .then((response) => {
                        commit('SET_QUESTS', response.data)
                    })
            }
        },
        next({ commit, state }) {
            if (state.quests != null && state.quests.meta.current_page < state.quests.meta.last_page) {
                axios.get(`/api/campaign/quests?page[number]=${state.quests.meta.current_page + 1}`)
                    .then((response) => {
                        commit('SET_QUESTS', response.data)
                    })
            }
        },
        load({ commit }) {
            axios.get('/api/campaign/quests')
                .then((response) => {
                    commit('SET_QUESTS', response.data)
                })
        },
        find({ commit }, questId) {
            return axios.get(`/api/campaign/quests/${questId}`)
                .then((response) => {
                    return response.data
                })
        },
        store({ commit, dispatch }, quest) {
            return axios.post('/api/campaign/quests', quest)
                .then(() => {
                    commit('SET_ERRORS', {})
                    dispatch('Messages/success', 'Quest saved!', { root: true })
                })
        },
        update({ commit, dispatch }, { quest, id }) {
            const payload = { ...quest, _method: 'put' }
            return axios.post(`/api/campaign/quests/${id}`, payload)
                .then(() => {
                    state.errors = {}
                    dispatch('Messages/success', 'Quest saved!', { root: true })
                })
        },
        destroy({ dispatch }, quest) {
            return axios.delete(`/api/campaign/quests/${quest.id}`)
                .then(() => {
                    dispatch('Messages/success', 'Quest successfully deleted!', { root: true })
                })
        }
    },
    mutations: {
        SET_ERRORS(state, errors) {
            state.errors = errors
        },
        SET_QUESTS(state, quests) {
            state.quests = quests;
        }
    }
};