import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useMessageStore } from '../../../../stores/messages'

export const useNoteFormState = (store, can) => {
    const router = useRouter()
    return reactive({
        errors: {},
        setErrors(errors) {
            this.errors = errors
        },
        input: {},
        init(id) {
            if (id) {
                store.find(id)
                    .then((note) => {
                        this.input = { ...note }
                    })
            } else {
                this.input = {}
            }
        },
        save() {
            let promise
            const note = {
                name: this.input.name,
                content: this.input.content,
                private: this.input.private
            }

            if (can('edit', 'role')) {
                note.permissions = this.input.permissions || {}
            }

            if (this.input.id) {
                promise = store.update({ id: this.input.id, note })
            } else {
                promise = store.store(note)
            }

            promise
                .then(() => {
                    if (Object.keys(this.errors).length === 0) {
                        router.push({ name: 'notes' })
                    }
                })
                .catch((error) => {
                    const messages = useMessageStore()
                    messages.error(error.response.data.message)
                })
        }
    })
}