import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const useInventory = (store) => {
    const state = reactive({
        editMode: false,
        input: {},
        inventory: {},
        cancel() {
            this.setInput({
                platinum: this.inventory.platinum || 0,
                gold: this.inventory.gold || 0,
                silver: this.inventory.silver || 0,
                copper: this.inventory.copper || 0
            })
            this.setEditMode(false)
        },
        setInventory(inventory) {
            this.inventory = inventory
            this.setInput({
                platinum: inventory.platinum || 0,
                gold: inventory.gold || 0,
                silver: inventory.silver || 0,
                copper: inventory.copper || 0
            })
        },
        setEditMode(mode) {
            this.editMode = mode
        },
        setInput(input) {
            this.input = input
        },
        savePurse() {
            store.update({ id: this.inventory.id, input: this.input })
                .then(() => {
                    this.setInventory({ ...this.inventory, ...this.input })
                    this.setEditMode(false)
                })
        }
    })
    return {
        state,
        weapons: computed(() => {
            if (!state.inventory?.items) {
                return []
            }
            return state.inventory.items.filter(item => item.category === 'Weapons')
        }),
        armor: computed(() => {
            if (!state.inventory?.items) {
                return []
            }
            return state.inventory.items.filter(item => item.category === 'Armor')
        }),
        potions: computed(() => {
            if (!state.inventory?.items) {
                return []
            }
            return state.inventory.items.filter(item => item.category === 'Potions')
        }),
        other: computed(() => {
            if (!state.inventory?.items) {
                return []
            }
            return state.inventory.items.filter(item => !['Armor', 'Potions', 'Weapons'].includes(item.category))
        }),
        coins: computed(() => {
            const main = useMainStore()
            const { campaign } = storeToRefs(main)
            const coins = ['platinum', 'gold', 'silver', 'copper']
            if (campaign.use_electrum) {
                coins.splice(2, 0, 'electrum')
            }
            return coins
        })
    }
}