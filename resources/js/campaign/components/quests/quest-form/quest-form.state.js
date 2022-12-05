import { debounce } from 'lodash'
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useMessageStore } from '../../../../stores/messages'

export const useQuestFormState = (store, can) => {
    const router = useRouter()
    const state = reactive({
        init(id) {
            if (id) {
                store.find(id)
                    .then((quest) => {
                        this.input = reactive({ ...quest })
                        if (!quest.hasOwnProperty('permissions')) {
                            this.input.permissions = {}
                        }
                    })
            } else {
                this.input = {
                    objectives: [],
                    permissions: {}
                }
                this.addObjective()
            }
        },
        errors: {},
        setErrors(errors) {
            this.errors = errors
        },
        input: {},
        addObjective() {
            this.input.objectives.push({ name: '', optional: false })
        },
        removeObjective(index) {
            this.input.objectives.splice(index, 1)
        },
        save() {
            let promise
            const input = {
                title: this.input.title,
                description: this.input.description,
                objectives: [],
                private: this.input.private || false
            }
            if (can('edit', 'role')) {
                input.permissions = this.input.permissions || {}
            }
            if (this.input.location_id > 0) {
                input.location_id = this.input.location_id
            }

            for (const objective of this.input.objectives) {
                if (objective.title != '') {
                    input.objectives.push(objective)
                }
            }
            if (this.input.id) {
                promise = store.update({ quest: input, id: this.input.id })
            } else {
                promise = store.store(input)
            }
            promise
                .then(() => {
                    store.load()
                    router.push({ name: 'quests' })
                })
                .catch((error) => {
                    const messages = useMessageStore()
                    messages.error(error.response.data.message)
                    this.setErrors(error.response.data.errors)
                })
        }
    })

    return { state }
}

export const useSearch = () => {
    return {
        locations: reactive([]),
        search: debounce((query, loading) => {
            return axios.get(`/api/campaign/locations?filter[query]=${escape(query)}&page[number]=1&page[size]=10`)
                .then((response) => {
                    const locations = response.data.data.map((item) => (
                        { value: item.id, label: item.name }
                    ))
                    loading(false)
                    return locations
                })
        }, 1000)
    }
}