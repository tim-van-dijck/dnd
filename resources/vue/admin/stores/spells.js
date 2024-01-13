import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/admin/spells'

export const useSpellStore = defineStore('Spells', {
    state: () => (
        {
            spells: null
        }
    ),
    actions: {
        find(id) {
            if ((
                this.spells?.data || []
            ).length > 0) {
                const spell = this.spells.data.find((item) => item.id === id)
                if (spell) {
                    return Promise.resolve(spell)
                }
            }
            return axios.get(`${url}/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        store(spell) {
            return axios.post(url, spell)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Spell saved!')
                })
        },
        update({ id, spell }) {
            return axios.put(`${url}/${id}`, spell)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Spell saved!')
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    this.load()
                        .then(() => {
                            const messages = useMessageStore()
                            messages.success('Spell successfully deleted!')
                        })
                })
        },

        previous() {
            if (this.spells != null && this.spells.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.spells.meta.current_page - 1}`)
                    .then((response) => {
                        this.$patch({
                            spells: response.data
                        })
                    })
            }
        },
        page(number) {
            if (this.spells != null && number > 0 && number <= this.spells.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        this.$patch({
                            spells: response.data
                        })
                    })
            }
        },
        next() {
            if (this.spells != null && this.spells.meta.current_page < this.spells.meta.last_page) {
                axios.get(`${url}?page[number]=${this.spells.meta.current_page + 1}`)
                    .then((response) => {
                        this.$patch({
                            spells: response.data
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
                        spells: response.data
                    })
                })
        }
    }
})