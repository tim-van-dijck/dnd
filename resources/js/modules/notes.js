export const Notes = {
    namespaced: true,
    state: {
        errors: {},
        notes: null
    },
    actions: {
        previous({commit,state}) {
            if (state.notes != null && state.notes.meta.current_page > 1) {
                axios.get(`/campaign/notes?page[number]=${state.notes.meta.current_page - 1}`)
                    .then((response) => {
                        commit('SET_NOTES', response.data);
                    })
            }
        },
        page({commit,state}, number) {
            if (state.notes != null && number > 0 && number <= state.notes.meta.last_page)
                axios.get(`/campaign/notes?page[number]=${number}`)
                    .then((response) => {
                        commit('SET_NOTES', response.data);
                    })
        },
        next({commit,state}) {
            if (state.notes != null && state.notes.meta.current_page < state.notes.meta.last_page) {
                axios.get(`/campaign/notes?page[number]=${state.notes.meta.current_page + 1}`)
                    .then((response) => {
                        commit('SET_NOTES', response.data);
                    })
            }
        },
        load({commit}) {
            axios.get('/campaign/notes')
                .then((response) => {
                    commit('SET_NOTES', response.data);
                });
        },
        find({}, id) {
            return axios.get(`/campaign/notes/${id}`)
                .then((response) => {
                    return response.data;
                })
        },
        store({commit, dispatch}, note) {
            return axios.post('/campaign/notes', note)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Note saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        update({commit, dispatch}, data) {
            let payload = data.note;
            payload._method = 'put';
            return axios.post(`/campaign/notes/${data.id}`, payload)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Note saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        destroy({dispatch}, note) {
            return axios.delete(`/campaign/notes/${note.id}`)
                .then(() => {
                    dispatch('Messages/success', 'Note successfully deleted!', {root: true});
                })
        }
    },
    mutations: {
        SET_ERRORS(state, errors) {
            state.errors = errors;
        },
        SET_NOTES(state, notes) {
            state.notes = notes;
        }
    }
}