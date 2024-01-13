import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { computed, reactive } from 'vue'

export const useAddPotionModalState = (store, inventoryId) => {
    const { items } = storeToRefs(store)
    const state = reactive({
        quantity: 1,
        potionId: 0,
        errors: {},
        reset() {
            this.quantity = 1
            this.potionId = 0
            this.errors = {}
        },
        setErrors(errors) {
            this.errors = errors
        },
        addPotion() {
            store.add({ inventoryId, item: selectedPotion.value, quantity: this.quantity })
                .then(() => {
                    this.setErrors({})
                    UIKit.modal('#add-potion-modal').hide()
                })
                .catch((exception) => {
                    this.setErrors(exception.response.data.errors)
                })
        }
    })

    const selectedPotion = computed(() => {
        if (state.potionId <= 0) {
            return null
        }
        return items.value?.potions.find(potion => potion.id === state.potionId)
    })
    const potionOptions = computed(() => {
        const potionOptions = items.value?.potions || []
        let grouped = {
            Potions: [],
            Poison: [],
            Miscellaneous: []
        }
        for (const potion of potionOptions) {
            if (!grouped.hasOwnProperty(potion.type)) {
                grouped[potion.type] = []
            }
            grouped[potion.type].push(potion)
        }
        return grouped
    })

    return { potionOptions, selectedPotion, state }
}