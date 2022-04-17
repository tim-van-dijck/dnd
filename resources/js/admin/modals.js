import UIKit from "uikit";
import {useStore} from "vuex";

export const confirmDelete = (entity, callback) => {
    UIKit.modal.prompt(
        `Are you sure you want to delete this ${entity}? Please write DELETE to confirm`, '',
        {
            labels: {
                ok: 'Delete',
                cancel: 'cancel'
            }
        }
    ).then(() => {
        if (input === 'DELETE') {
            callback()
        } else {
            const store = useStore()
            store.dispatch('Messages/error', 'Invalid input, delete cancelled.')
        }
    })
}