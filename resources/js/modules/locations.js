export const Locations = {
    namespaced: true,
    state: {
        locations: null
    },
    actions: {
        loadLocations({commit}, data) {
            return axios.get(`/campaign/${data.campaign_id}/locations`)
                .then((response) => {
                    commit('SET_LOCATIONS', response.data)
                });
        },
        findLocation({commit}, data) {
            return axios.get(`/campaign/${data.campaign_id}/locations/${data.id}`)
                .then((response) => {
                    return response.data;
                });
        },
        storeLocation({dispatch}, data) {
            let form = new FormData();
            for (let prop of ['name', 'type', 'description', 'location_id', 'map']) {
                if (data.location[prop] != '' && data.location[prop] != null) {
                    form.append(prop, data.location[prop]);
                }
            }
            return axios.post(
                `/campaign/${data.campaign_id}/locations`,
                form,
                {
                    headers: {'content-type': 'multipart/form-data'}
                })
                .then(() => {
                    dispatch('Locations/loadLocations');
                });
        },
        updateLocation({dispatch}, data) {
            return axios.put(`/campaign/${data.campaign_id}/locations/${data.location_id}`, data)
                .then(() => {
                    dispatch('Locations/loadLocations');
                });
        }
    },
    mutations: {
        SET_LOCATIONS(state, locations) {
            state.locations = locations || null;
        }
    }
};
