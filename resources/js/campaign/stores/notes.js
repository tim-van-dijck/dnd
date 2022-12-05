import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/notes'

export const useNoteStore = defineStore('campaign-notes', {
    state: () => (
        {
            notes: null
        }
    ),
    actions: {
        previous() {
            if (this.notes != null && this.notes.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.notes.meta.current_page - 1}`)
                    .then((response) => {
                        this.notes = response.data
                    })
            }
        },
        page(number) {
            if (this.notes != null && number > 0 && number <= this.notes.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        this.notes = response.data
                    })
            }
        },
        next() {
            if (this.notes != null && this.notes.meta.current_page < this.notes.meta.last_page) {
                axios.get(`${url}?page[number]=${this.notes.meta.current_page + 1}`)
                    .then((response) => {
                        this.notes = response.data
                    })
            }
        },
        load() {
            axios.get('/api/campaign/notes')
                .then((response) => {
                    this.notes = response.data
                })
        },
        find(id) {
            return axios.get(`${url}/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        store(note) {
            return axios.post(url, note)
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Note saved!')
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        update({ note, id }) {
            return axios.put(`${url}/${id}`, note)
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Note saved!')
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        destroy(id) {
            return axios.delete(`/api/campaign/notes/${id}`)
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Note successfully deleted!')
                })
        }
    }
})