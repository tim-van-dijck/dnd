import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { computed, reactive } from 'vue'

export const useAddWeaponModalState = (inventoryId, store) => {
    const { items } = storeToRefs(store)
    const state = reactive({
        quantity: 1,
        weaponId: 0,
        errors: {},
        reset() {
            this.quantity = 1
            this.weaponId = 0
            this.errors = {}
        },
        setErrors(errors) {
            this.errors = errors
        },
        addWeapon() {
            store.add({ inventoryId, item: selectedWeapon.value, quantity: this.quantity })
                .then(() => {
                    this.setErrors({})
                    UIKit.modal('#add-weapon-modal').hide()
                })
                .catch((exception) => {
                    this.setErrors(exception.response.data.errors)
                })
        }
    })

    const selectedWeapon = computed(() => {
        if (state.weaponId <= 0) {
            return null
        }
        return items.value?.weapons.find(weapon => weapon.id === state.weaponId)
    })
    const formattedProperties = computed(() => {
        if (!selectedWeapon.value) {
            return []
        }
        const result = []
        for (const property in selectedWeapon.value.properties) {
            const value = selectedWeapon.value.properties[property]
            switch (property) {
                case 'damage':
                case 'damage_dice':
                case 'damage_type':
                    break
                case 'dual_wield':
                    result.push('Light')
                    break
                case 'two_handed':
                    result.push('Two-Handed')
                    break
                case 'range':
                    result.push(`Range (${value})`)
                    break
                case 'versatile':
                    result.push(`Versatile (${value})`)
                    break
                case 'special':
                    result.push('Special')
                    break
                default:
                    result.push(property.replace(/^\w/, (c) => c.toUpperCase()))
                    break
            }
        }
        return result
    })
    const weaponOptions = computed(() => {
        const weapons = items.value?.weapons || []
        const grouped = {}
        for (const weapon of weapons) {
            if (!grouped.hasOwnProperty(weapon.type)) {
                grouped[weapon.type] = []
            }
            grouped[weapon.type].push(weapon)
        }
        return grouped
    })

    return { formattedProperties, selectedWeapon, state, weaponOptions }
}