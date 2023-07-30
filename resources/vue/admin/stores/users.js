import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = `/api/admin/users`

export const useUserStore = defineStore('Users', {
    state: () => (
        {
            users: null
        }
    ),
    actions: {
        find(id) {
            if ((
                this?.users?.data || []
            ).length > 0) {
                let user = this.users.data.find((item) => item.id === id)
                if (user) {
                    return Promise.resolve(user)
                }
            }
            return axios.get(`${url}/${id}`)
                .then((response) => {
                    return response.data.data
                })
        },
        invite(user) {
            return axios.post(`${url}/invite`, user)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('User saved!')
                })
        },
        update({ id, user }) {
            return axios.put(`${url}/${id}`, user)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('User saved!')
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    this.load()
                        .then(() => {
                            const messages = useMessageStore()
                            messages.success('User successfully deleted!')
                        })
                })
        },
        resetPassword(id) {
            return axios.post(`${url}/reset-password`, { user_id: id })
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Password reset link sent!')
                })
        },

        previous() {
            if (this.users != null && this.users.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.users.meta.current_page - 1}`)
                    .then((response) => {
                        this.users = response.data
                    })
            }
        },
        page(number) {
            if (this.users != null && number > 0 && number <= this.users.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        this.users = response.data
                    })
            }
        },
        next() {
            if (this.users != null && this.users.meta.current_page < this.users.meta.last_page) {
                axios.get(`${url}?page[number]=${this.users.meta.current_page + 1}`)
                    .then((response) => {
                        this.users = response.data
                    })
            }
        },
        load(filters) {
            let params = {}
            for (let key in filters || {}) {
                params[`filters[${key}]`] = filters[key]
            }
            let query = new URLSearchParams(params)
            return axios.get(`${url}?${query}`)
                .then((response) => {
                    this.users = response.data
                })
        }
    }
})