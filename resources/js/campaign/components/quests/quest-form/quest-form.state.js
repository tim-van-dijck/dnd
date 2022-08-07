import { debounce } from 'lodash'
import { reactive } from 'vue'
import { useRouter } from 'vue-router'

export const useQuestFormState = (store) => {
    const state = reactive({
        input: {},
        addObjective() {
            this.input.objectives.push({ name: '', optional: false })
        },
        removeObjective(index) {
            this.input.objectives.splice(index, 1)
        },
        save: (quest) => save(store, quest)
    })

    return { state }
}


const save = (store, quest) => {
    const router = useRouter()

    let promise
    const input = {
        title: quest.title,
        description: quest.description,
        objectives: [],
        private: quest.private
    }
    if (store.getters.can('edit', 'role')) {
        input.permissions = quest.permissions || {}
    }
    if (quest.location_id > 0) {
        input.location_id = quest.location_id
    }

    for (const objective of quest.objectives) {
        if (objective.title != '') {
            input.objectives.push(objective)
        }
    }
    if (quest.id) {
        promise = store.dispatch('Quests/update', { quest: input, id: quest.id })
    } else {
        promise = store.dispatch('Quests/store', input)
    }
    promise
        .then(() => {
            store.dispatch('Quests/load')
            router.push({ name: 'quests' })
        })
        .catch((error) => {
            store.commit('Quests/SET_ERRORS', error.response.data.errors)
            store.dispatch('Messages/error', error.response.data.message, { root: true })
        })
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