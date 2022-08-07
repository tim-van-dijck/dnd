import { generatePaginatedActions } from '../../generators/StateActionGenerator'

export const Spells = {
    namespaced: true,
    state: () => (
        {
            spells: null,
            errors: {}
        }
    ),
    actions: {
        ...generatePaginatedActions('/api/admin/spells', 'spells'),

        find({ state }, id) {
            if ((
                state?.spells?.data || []
            ).length > 0) {
                let spell = state.spells.data.find((item) => item.id === id)
                if (spell) {
                    return Promise.resolve(spell)
                }
            }
            return axios.get(`/api/admin/spells/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        store({ dispatch, commit }, data) {
            return axios.post(`/api/admin/spells`, data.spell)
                .then(() => {
                    commit('SET_ERRORS', {})
                    dispatch('Messages/success', 'Spell saved!', { root: true })
                })
        },
        update({ dispatch, commit }, data) {
            return axios.put(`/api/admin/spells/${data.id}`, data.spell)
                .then(() => {
                    commit('SET_ERRORS', {})
                    dispatch('Messages/success', 'Spell saved!', { root: true })
                })
        },
        destroy({ dispatch }, spell) {
            return axios.delete(`/api/admin/spells/${spell.id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            dispatch('Messages/success', 'Spell successfully deleted!', { root: true })
                        })
                })
        }
    },
    mutations: {
        SET_SPELLS(state, spells) {
            state.spells = spells
        },
        SET_ERRORS(state, errors) {
            state.errors = errors
        }
    }
}