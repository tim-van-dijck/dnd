import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/journal'

export const useJournalStore = defineStore('campaign-journal', {
    state: () => (
        {
            entries: null
        }
    ),
    actions: {
        load() {
            return axios.get(url)
                .then((response) => {
                    this.entries = response.data
                })
        },
        find(entryId) {
            return axios.get(`${url}/${entryId}`)
                .then((response) => {
                    return response.data
                })
        },
        store(entry) {
            return axios.post(url, entry)
                .then((response) => {
                    if (this.entries === null) {
                        this.entries = [response.data]
                    } else {
                        this.entries.push(response.data)
                    }
                    const messageStore = useMessageStore()
                    messageStore.success('Entry saved!')
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        update({ id, entry }) {
            return axios.put(`${url}/${data.id}`, entry)
                .then((response) => {
                    const index = this.entries.findIndex((entry) => entry.id === id)
                    if (index) {
                        this.entries.splice(index, 1, response.data)
                    }
                    const messageStore = useMessageStore()
                    messageStore.success('Entry saved!')
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Entry successfully deleted!')
                })
        },
        sort() {
            if (this.entries === null) {
                return Promise.resolve()
            }

            const order = this.entries.map((entry) => entry.id)

            return axios.post(`${url}/sort`, { list: order })
                .then(() => {
                    for (const entry of this.entries) {
                        entry.order = order.find(item => item === entry.id)?.order
                    }
                })
        }
    }
})