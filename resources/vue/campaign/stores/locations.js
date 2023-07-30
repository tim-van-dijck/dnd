import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/locations'

export const useLocationStore = defineStore('campaign-locations', {
    state: () => (
        {
            locations: null
        }
    ),
    actions: {
        previous() {
            if (this.locations != null && this.locations.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.locations.meta.current_page - 1}`)
                    .then((response) => {
                        this.locations = response.data
                    })
            }
        },
        page(number) {
            if (this.locations != null && number > 0 && number <= this.locations.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        this.locations = response.data
                    })
            }
        },
        next() {
            if (this.locations != null && this.locations.meta.current_page < this.locations.meta.last_page) {
                axios.get(`${url}?page[number]=${this.locations.meta.current_page + 1}`)
                    .then((response) => {
                        this.locations = response.data
                    })
            }
        },
        load() {
            return axios.get(url)
                .then((response) => {
                    this.locations = response.data
                })
        },
        find(id) {
            return axios.get(`${url}/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        store(location) {
            return axios.post(
                url,
                location,
                {
                    headers: { 'content-type': 'multipart/form-data' }
                }
            )
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Location saved!')
                })
                .catch((exception) => {
                    const messageStore = useMessageStore()
                    messageStore.error(exception.response.data.message)
                    throw exception
                })
        },
        update({ id, location }) {
            location.append('_method', 'put')
            return axios.post(
                `/api/campaign/locations/${id}`,
                location,
                {
                    headers: { 'content-type': 'multipart/form-data' }
                }
            )
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Location saved!')
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
                    messageStore.success('Location successfully deleted!')
                })
        }
    }
})
