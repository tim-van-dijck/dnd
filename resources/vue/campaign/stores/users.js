import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = '/api/campaign/users'

export const useUserStore = defineStore('campaign-users', {
    state: () => (
        {
            users: {}
        }
    ),
    actions: {
        load() {
            return axios.get(url)
                .then((response) => {
                    this.users = response.data
                })
        },
        find(id) {
            return axios.get(`${url}/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        invite(user) {
            return axios.post(`${url}/invite`, user)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('Invite sent!')
                })
                .catch((exception) => {
                    const messages = useMessageStore()
                    messages.error(exception.response.data.errors)
                    throw exception
                })
        },

        update({ id, user }) {
            return axios.put(`${url}/${id}`, user)
                .then(() => {
                    const messages = useMessageStore()
                    messages.success('User successfully saved!')
                })
                .catch((exception) => {
                    const messages = useMessageStore()
                    messages.error(exception.response.data.errors)
                    throw exception
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
        }
    }
})