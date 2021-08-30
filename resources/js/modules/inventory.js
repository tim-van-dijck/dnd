export const Inventory = {
    namespaced: true,
    state: {
        characterInventories: null
    },
    actions: {
        load({state}) {
            return axios.get('/campaign/inventories')
                .then((response) => {
                    state.characterInventories = response.data
                });
        },
        find({state}, id) {
            return axios.get(`/campaign/inventories/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        save({dispatch}, payload) {
            return axios.put(`/campaign/inventories/${payload.id}`, payload.inventory);
        }
    }
}