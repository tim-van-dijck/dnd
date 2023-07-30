import UIkit from 'uikit'
import { reactive } from 'vue'

export const useInviteModalState = (store) => {
    return reactive({
        user: { ...emptyUser },
        errors: {},
        setErrors(errors) {
            this.errors = errors
        },
        open() {
            this.user = { ...emptyUser }
            UIkit.modal('#user-modal').show()
        },
        cancel() {
            this.user = { ...emptyUser }
            UIkit.modal('#user-modal').hide()
        },
        save() {
            return store.invite(this.user)
                .then(() => {
                    UIkit.modal('#user-modal').hide()
                    return this.user
                })
                .catch((exception) => {
                    this.setErrors(exception.response.data.errors)
                })
        }
    })
}

const emptyUser = {
    email: '',
    admin: false
}