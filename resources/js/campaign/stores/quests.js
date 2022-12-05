import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/quests'

export const useQuestStore = defineStore('campaign-quest', {
    state: () => (
        {
            quests: null
        }
    ),
    actions: {
        find(questId) {
            return axios.get(`${url}/${questId}`)
                .then((response) => {
                    return response.data
                })
        },
        store(quest) {
            return axios.post(url, quest)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Quest saved!')
                })
        },
        update({ quest, id }) {
            return axios.put(`${url}/${id}`, quest)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Quest saved!')
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Quest successfully deleted!')
                })
        },
        toggleObjective(questId, objectiveId, status) {
            return axios.post(`${url}/${questId}/objectives/${objectiveId}/toggle`, { status })
        },

        previous() {
            if (this.quests != null && this.quests.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.quests.meta.current_page - 1}`)
                    .then((response) => {
                        this.quests = response.data
                    })
            }
        },
        page(number) {
            if (this.quests != null && number > 0 && number <= this.quests.meta.last_page) {
                axios.get(`${url}s?page[number]=${number}`)
                    .then((response) => {
                        this.quests = response.data
                    })
            }
        },
        next() {
            if (this.quests != null && this.quests.meta.current_page < this.quests.meta.last_page) {
                axios.get(`${url}?page[number]=${this.quests.meta.current_page + 1}`)
                    .then((response) => {
                        this.quests = response.data
                    })
            }
        },
        load() {
            axios.get(url)
                .then((response) => {
                    this.quests = response.data
                })
        }
    }
})