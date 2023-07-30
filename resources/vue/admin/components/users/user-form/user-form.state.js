import { reactive } from 'vue'
import { useRouter } from 'vue-router'

export const useUserFormState = (store, messages) => {
    const router = useRouter()

    const state = reactive({
        user: null,
        errors: {},
        setUser(user) {
            this.user = user
        },
        save() {
            const { id, ...user } = this.user
            const promise = id ? store.update({ id: id || null, user }) : store.store(user)
            promise
                .then(() => router.push({ name: 'users' }))
                .catch((error) => {
                    this.errors = error.response.data.errors
                    messages.error(error.response.data.message)
                })
        }
    })

    return { state }
}