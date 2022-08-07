import { defineStore } from 'pinia/dist/pinia.esm-browser'
import { useMessageStore } from '../../stores/messages'

const url = `/api/admin/campaigns`

export const useCampaignStore = defineStore('Campaigns', {
    state: () => (
        {
            campaigns: null,
            userCampaigns: {}
        }
    ),
    actions: {
        previous() {
            if (this.campaigns != null && this.campaigns.meta.current_page > 1) {
                axios.get(`${url}?page[number]=${this.campaigns.meta.current_page - 1}`)
                    .then((response) => {
                        this.campaigns = response.data
                    })
            }
        },
        page(number) {
            if (this.campaigns != null && number > 0 && number <= this.campaigns.meta.last_page) {
                axios.get(`${url}?page[number]=${number}`)
                    .then((response) => {
                        this.campaigns = response.data
                    })
            }
        },
        next() {
            if (this.campaigns != null && this.campaigns.meta.current_page < this.campaigns.meta.last_page) {
                axios.get(`${url}?page[number]=${this.campaigns.meta.current_page + 1}`)
                    .then((response) => {
                        this.campaigns = response.data
                    })
            }
        },
        load() {
            return axios.get(url)
                .then((response) => {
                    this.campaigns = response.data
                })
        },
        loadForUser(userId) {
            if (this.userCampaigns.hasOwnProperty(userId)) {
                return Promise.resolve(this.userCampaigns[userId])
            }
            return axios.get(`${url}?filters[user_id]=${userId}`)
                .then((response) => {
                    this.userCampaigns[userId] = response.data.data
                    return response.data.data
                })
        },
        find(id) {
            if ((
                this.campaigns?.data || []
            ).length > 0) {
                const campaign = this.campaigns.data.find((item) => item.id === id)
                if (campaign) {
                    return Promise.resolve(campaign)
                }
            }
            return axios.get(`${url}/${id}`)
                .then((response) => {
                    return response.data
                })
        },

        store(campaign) {
            return axios.post(url, campaign)
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Campaign saved!')
                })
        },
        update({ id, campaign }) {
            return axios.put(`${url}/${id}`, campaign)
                .then(() => {
                    const messageStore = useMessageStore()
                    messageStore.success('Campaign saved!')
                })
        },
        destroy(id) {
            return axios.delete(`${url}/${id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            const messageStore = useMessageStore()
                            messageStore.success('Campaign successfully deleted!')
                        })
                })
        }
    },
    getters: {
        userCampaigns: (state) => (userId) => state.userCampaigns[userId] || []
    }
})