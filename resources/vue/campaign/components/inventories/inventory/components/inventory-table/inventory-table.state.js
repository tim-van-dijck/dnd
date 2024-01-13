import UIKit from 'uikit'
import { reactive } from 'vue'

export const useInventoryTableState = (store, inventoryId, type) =>
    reactive({
        selected: null,
        removeQuantity: 1,
        confirm(selection) {
            this.selected = selection
            UIKit.modal(`#remove-item-modal-${type}`).show()
        },
        deselect() {
            this.selected = null
        },
        remove() {
            const payload = {
                inventoryId: inventoryId,
                id: this.selected.id,
                quantity: this.removeQuantity
            }

            store.remove(payload)
                .then(() => {
                    UIKit.modal(`#remove-item-modal-${type}`).hide()
                })
        }
    })