import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { computed, reactive } from 'vue'

export const useAddArmorModalState = (inventoryId, store) => {
    const { items } = storeToRefs(store)
    const state = reactive({
        quantity: 1,
        armorId: 0,
        errors: {},
        reset() {
            this.quantity = 1
            this.weaponId = 0
            this.errors = {}
        },
        setErrors(errors) {
            this.errors = errors
        },
        addArmor() {
            store.add({ inventoryId, item: selectedArmor.value, quantity: this.quantity })
                .then(() => {
                    this.setErrors({})
                    UIKit.modal('#add-armor-modal').hide()
                })
                .catch((exception) => {
                    this.setErrors(exception.response.data.errors)
                })
        }
    })

    const selectedArmor = computed(() => {
        if (state.armorId <= 0) {
            return null
        }
        return items.value?.armor.find(pieceOfArmor => pieceOfArmor.id === state.armorId)
    })
    const formattedProperties = computed(() => {
        if (!selectedArmor.value) {
            return []
        }
        const result = []
        for (const property in selectedArmor.value.properties) {
            const value = selectedArmor.value.properties[property]
            switch (property) {
                case 'ac':
                case 'add_dex':
                    break
                case 'strength':
                    result.push({ label: `Min. Strength: ${value}`, type: 'warning' })
                    break
                case 'stealth_disadvantage':
                    let title = value ? 'Disadvantage on Stealth' : 'No disadvantage on Stealth'
                    result.push({ label: 'Stealth', type: value ? 'danger' : 'success', title })
                    break
                case 'don':
                case 'doff':
                    const propertyName = property.replace(/^\w/, (c) => c.toUpperCase())
                    result.push({ label: `${propertyName}: (${value})` })
                    break
            }
        }
        return result
    })
    const armorOptions = computed(() => {
        const armorOptions = items.value?.armor || []
        const grouped = {
            Light: [],
            Medium: [],
            Heavy: [],
            Shield: []
        }
        for (const armor of armorOptions) {
            if (!grouped.hasOwnProperty(armor.type)) {
                grouped[armor.type] = []
            }
            grouped[armor.type].push(armor)
        }
        return grouped
    })

    return { armorOptions, formattedProperties, selectedArmor, state }
}