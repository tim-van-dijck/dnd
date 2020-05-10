export const Locations = {
    namespaced: true,
    state: {
        errors: {},
        locations: null
    },
    actions: {
        loadLocations({commit}) {
            return axios.get(`/campaign/locations`)
                .then((response) => {
                    commit('SET_LOCATIONS', response.data)
                });
        },
        find(id) {
            return axios.get(`/campaign/locations/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        store({dispatch, commit}, data) {
            return axios.post(
                `/campaign/locations`,
                data.location,
                {
                    headers: {'content-type': 'multipart/form-data'}
                })
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Location saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        update({dispatch, commit}, data) {
            data.location.append('_method', 'put');
            return axios.post(
                `/campaign/locations/${data.id}`,
                data.location,
                {
                    headers: {'content-type': 'multipart/form-data'}
                })
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Location saved!', {root: true});
                })
                .catch((error) => {
                    commit('SET_ERRORS', error.response.data.errors);
                    dispatch('Messages/error', error.response.data.message, {root: true});
                });
        },
        destroy({dispatch}, location) {
            axios.delete(`/campaign/locations/${location.id}`)
                .then(() => {
                    dispatch('loadLocations')
                        .then(() => {
                            dispatch('Messages/success', 'Location successfully deleted!', {root: true});
                        });
                })
        }
    },
    mutations: {
        SET_ERRORS(state, errors) {
            state.errors = errors;
        },
        SET_LOCATIONS(state, locations) {
            state.locations = locations || null;
        }
    }
};
