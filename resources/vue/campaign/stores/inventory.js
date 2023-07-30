import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/inventories'

export const useInventoryStore = defineStore('campaign-inventory', {
    state: () => (
        {
            characterInventories: null,
            items: {
                weapons: null,
                armor: null,
                potions: null
            }
        }
    ),
    actions: {
        load() {
            return axios.get(url)
                .then((response) => {
                    this.characterInventories = response.data.data
                })
        },
        find(id) {
            const existing = this.characterInventories?.find(inventory => parseInt(inventory.id) === parseInt(id))
            if (id && !existing) {
                return axios.get(`${url}/${id}`)
                    .then((response) => {
                        this.characterInventories = [...this.characterInventories || [], response.data.data]
                        return response.data.data
                    })
            }
            return Promise.resolve(existing)
        },
        inventory(id) {
            return this.characterInventories?.find(inventory => parseInt(inventory.id) === parseInt(id))
        },
        update({ id, input }) {
            return axios.put(`${url}/${id}`, input)
                .then((response) => {
                    const index = this.characterInventories?.findIndex((inventory) =>
                        parseInt(inventory.id) === parseInt(id))
                    this.characterInventories[index] = (
                        response?.data?.data || {}
                    )
                    const messageStore = useMessageStore()
                    messageStore.success('Inventory updated!')
                    return this.characterInventories[index]
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        add({ quantity, inventoryId, item }) {
            return axios.post(`${url}/${inventoryId}/items`, { id: item.id, quantity })
                .then(() => {
                    const inventory = this.inventory(inventoryId)
                    const index = this.characterInventories.indexOf(inventory)

                    const existingItem = inventory.items.find(invItem => item.id === invItem.id)
                    if (existingItem) {
                        existingItem.quantity += quantity
                    } else {
                        inventory.items.push({ ...item, quantity })
                    }
                    this.characterInventories[index] = inventory
                })
        },
        remove({ id, quantity, inventoryId }) {
            return axios.delete(
                `${url}/${inventoryId}/items`,
                { data: { id, quantity } }
            )
                .then(() => {
                    const inventory = this.inventory(inventoryId)
                    const index = this.characterInventories.indexOf(inventory)
                    const existingItem = inventory.items.find(item => id === item.id)

                    if (existingItem) {
                        existingItem.quantity -= quantity
                        if (existingItem.quantity === 0) {
                            inventory.items.splice(inventory.items.indexOf(existingItem), 1)
                        }
                    }
                    this.characterInventories[index] = inventory
                })
        },
        loadItems(type) {
            return axios.get(`/api/campaign/items/${type}`)
                .then((response) => {
                    this.items[type] = response.data
                })
        }
    }
})