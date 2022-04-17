export const Journal = {
    namespaced: true,
    state: {
        entries: null,
        errors: {}
    },
    actions: {
        load({state}) {
            axios.get('/campaign/journal')
                .then((response) => {
                    state.entries = response.data;
                })
        },
        find({commit}, entryId) {
            return axios.get(`/campaign/journal/${entryId}`)
                .then((response) => {
                    return response.data;
                })
        },
        store({state, dispatch}, entry) {
            return axios.post('/campaign/journal', entry)
                .then((response) => {
                    if (state.entries === null) {
                        state.entries = [response.data];
                    } else {
                        state.entries.push(response.data)
                    }
                    state.errors = {};
                    dispatch('Messages/success', 'Entry saved!', {root: true});
                })
                .catch((error) => {
                    state.errors = error.response.data.errors || {};
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        update({state, dispatch}, data) {
            const payload = data.entry;
            payload._method = 'put';
            return axios.post(`/campaign/journal/${data.id}`, payload)
                .then((response) => {
                    const index = state.entries.findIndex(entry => entry.id === data.id);
                    if (index) {
                        state.entries.splice(index, 1, response.data);
                    }
                    state.errors = {};
                    dispatch('Messages/success', 'Entry saved!', {root: true});
                })
                .catch((error) => {
                    Vue.set(state, 'errors', error.response.data.errors || {});
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        destroy({dispatch}, entry) {
            return axios.delete(`/campaign/journal/${entry.id}`)
                .then(() => {
                    dispatch('Messages/success', 'Entry successfully deleted!', {root: true});
                })
        },
        sort({state}, event) {
            if (state.entries === null) {
                return;
            }
            const order = [];
            for (const entry of state.entries) {
                order.push(entry.id);
            }
            axios.post('/campaign/journal/sort', {list: order})
                .then(() => {
                    for (const entry of state.entries) {
                        entry.order = order.find(item => item === entry.id)?.order;
                    }
                })
        }
    }
}