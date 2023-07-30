import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { generatePaginatedActions } from '../../generators/StateActionGenerator'
import { useMessageStore } from '../../stores/messages'

const url = '/api/admin/races'

export const useRaceStore = defineStore('Races', {
    state: () => (
        {
            races: null,
            proficiencies: null,
            traits: null
        }
    ),
    actions: {
        ...generatePaginatedActions('/api/admin/races', 'races'),
        loadProficiencies() {
            return axios.get('/api/admin/proficiencies')
                .then((response) => {
                    this.$patch({
                        proficiencies: response.data.data
                    })
                })
        },
        loadTraits() {
            return axios.get(`${url}/traits`)
                .then((response) => {
                    this.$patch({
                        traits: response.data.data
                    })
                })
        },

        find(id) {
            if ((
                this.races?.data || []
            ).length > 0) {
                const race = this.races.data.find((item) => item.id === id)
                if (race) {
                    return Promise.resolve(race)
                }
            }
            return axios.get(`${url}/${id}?include=subraces,ability_bonuses,proficiencies,languages`)
                .then((response) => {
                    return response.data.data
                })
        },

        store(race) {
            return axios.post(url, race)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Race saved!')
                })
        },

        update({ id, race }) {
            return axios.put(`${url}/${id}`, race)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Race saved!')
                })
        },

        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    this.load()
                        .then(() => {
                            const messages = useMessageStore()
                            messages.success('Race successfully deleted!')
                        })
                })
        },

        previous() {
            if (this.races != null && this.races.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.races.meta.current_page - 1}`)
                    .then((response) => {
                        this.$patch({
                            races: response.data
                        })
                    })
            }
        },
        page(number) {
            if (this.races != null && number > 0 && number <= this.races.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        this.$patch({
                            races: response.data
                        })
                    })
            }
        },
        next() {
            if (this.races != null && this.races.meta.current_page < this.races.meta.last_page) {
                axios.get(`${url}?page[number]=${this.races.meta.current_page + 1}`)
                    .then((response) => {
                        this.$patch({
                            races: response.data
                        })
                    })
            }
        },
        load(filters) {
            const params = {}
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key]
            }
            const query = new URLSearchParams(params)
            return axios.get(`${url}?${query}`)
                .then((response) => {
                    this.$patch({
                        races: response.data
                    })
                })
        }
    }
})