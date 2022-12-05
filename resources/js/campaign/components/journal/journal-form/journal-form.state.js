import { reactive } from 'vue'
import { useRouter } from 'vue-router'

export const useJournalFormState = (store) => {
    const router = useRouter()
    const state = reactive({
        entry: null,
        errors: {},
        setEntry(entry) {
            this.entry = { ...entry }
        },
        save() {
            const { id, ...entry } = this.entry
            const promise = id ? store.update({ id: id || null, entry }) : store.store(entry)
            promise
                .then(() => router.push({ name: 'journal' }))
                .catch((exception) => {
                    this.errors = exception.response.data.errors
                })
        }
    })

    return state
}