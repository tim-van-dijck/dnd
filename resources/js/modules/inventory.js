import Vue from "vue";

export const Inventory = {
    namespaced: true,
    state: {
        characterInventories: null,
        items: {
            weapons: null,
            armor: null,
            potions: null
        }
    },
    actions: {
        load({state}) {
            return axios.get('/campaign/inventories')
                .then((response) => {
                    state.characterInventories = response.data.data
                });
        },
        find({state}, id) {
            const existing = state.characterInventories?.find(inventory => inventory.id == id);
            if (id && !existing) {
                return axios.get(`/campaign/inventories/${id}`)
                    .then((response) => {
                        Vue.set(state, 'characterInventories', [...state.characterInventories || [], response.data.data]);
                    });
            }
            return Promise.resolve(existing);
        },
        update({dispatch, state}, payload) {
            return axios.put(`/campaign/inventories/${payload.id}`, payload.input)
                .then((response) => {
                    const index = state.characterInventories?.findIndex(inventory => inventory.id == payload.id);
                    Vue.set(state.characterInventories, index, (response?.data?.data || {}));
                    dispatch('Messages/success', 'Inventory updated!', {root: true});
                    return state.characterInventories[index];
                })
        },
        add({dispatch, getters, state}, payload) {
            const item = payload.item;
            const quantity = parseInt(payload.quantity);

            return axios.post(`/campaign/inventories/${payload.inventoryId}/items`, {id: item.id, quantity})
                .then(() => {
                    const inventory = getters.inventory(payload.inventoryId);
                    const index = state.characterInventories.indexOf(inventory);

                    const existingItem = inventory.items.find(invItem => item.id === invItem.id);
                    if (existingItem) {
                        existingItem.quantity += quantity
                    } else {
                        inventory.items.push({...item, quantity})
                    }
                    Vue.set(state.characterInventories, index, inventory)
                });
        },
        remove({dispatch, getters, state}, payload) {
            const quantity = parseInt(payload.quantity);

            return axios.delete(`/campaign/inventories/${payload.inventoryId}/items`, {data: {id: payload.id, quantity}})
                .then(() => {
                    const inventory = getters.inventory(payload.inventoryId);
                    const index = state.characterInventories.indexOf(inventory);
                    const existingItem = inventory.items.find(item => payload.id === item.id);

                    if (existingItem) {
                        existingItem.quantity -= quantity;
                        if (existingItem.quantity === 0) {
                            inventory.items.splice(inventory.items.indexOf(existingItem), 1);
                        }
                    }
                    Vue.set(state.characterInventories, index, inventory)
                });
        },
        loadItems({state}, type) {
            return axios.get(`/campaign/items/${type}`)
                .then((response) => {
                    Vue.set(state.items, type, response.data);
                });
        }
    },
    getters: {
        inventory: (state) => (id) => {
            return state.characterInventories?.find(inventory => inventory.id == id);
        }
    }
}