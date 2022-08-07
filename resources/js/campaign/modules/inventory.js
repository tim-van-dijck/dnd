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
        load({ state }) {
            return axios.get('/api/campaign/inventories')
                .then((response) => {
                    state.characterInventories = response.data.data
                })
        },
        find({ state }, id) {
            const existing = state.characterInventories?.find(inventory => inventory.id == id)
            if (id && !existing) {
                return axios.get(`/api/campaign/inventories/${id}`)
                    .then((response) => {
                        state.characterInventories = [...state.characterInventories || [], response.data.data]
                    })
            }
            return Promise.resolve(existing)
        },
        update({ dispatch, state }, payload) {
            return axios.put(`/api/campaign/inventories/${payload.id}`, payload.input)
                .then((response) => {
                    const index = state.characterInventories?.findIndex(inventory => inventory.id == payload.id)
                    state.characterInventories[index] = (
                        response?.data?.data || {}
                    )
                    dispatch('Messages/success', 'Inventory updated!', { root: true })
                    return state.characterInventories[index]
                })
        },
        add({ dispatch, getters, state }, payload) {
            const item = payload.item
            const quantity = parseInt(payload.quantity)

            return axios.post(`/api/campaign/inventories/${payload.inventoryId}/items`, { id: item.id, quantity })
                .then(() => {
                    const inventory = getters.inventory(payload.inventoryId)
                    const index = state.characterInventories.indexOf(inventory)

                    const existingItem = inventory.items.find(invItem => item.id === invItem.id)
                    if (existingItem) {
                        existingItem.quantity += quantity
                    } else {
                        inventory.items.push({ ...item, quantity })
                    }
                    state.characterInventories[index] = inventory
                })
        },
        remove({ dispatch, getters, state }, payload) {
            const quantity = parseInt(payload.quantity)

            return axios.delete(
                `/api/campaign/inventories/${payload.inventoryId}/items`,
                { data: { id: payload.id, quantity } }
            )
                .then(() => {
                    const inventory = getters.inventory(payload.inventoryId)
                    const index = state.characterInventories.indexOf(inventory)
                    const existingItem = inventory.items.find(item => payload.id === item.id)

                    if (existingItem) {
                        existingItem.quantity -= quantity
                        if (existingItem.quantity === 0) {
                            inventory.items.splice(inventory.items.indexOf(existingItem), 1)
                        }
                    }
                    state.characterInventories[index] = inventory
                })
        },
        loadItems({ state }, type) {
            return axios.get(`/api/campaign/items/${type}`)
                .then((response) => {
                    state.items[type] = response.data
                })
        }
    },
    getters: {
        inventory: (state) => (id) => {
            return state.characterInventories?.find(inventory => inventory.id == id)
        }
    }
}