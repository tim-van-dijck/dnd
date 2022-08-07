export const Campaigns = {
    namespaced: true,
    state: () => (
        {
            campaigns: null,
            userCampaigns: {}
        }
    ),
    actions: {
        previous({ state }) {
            if (state.campaigns != null && state.campaigns.meta.current_page > 1) {
                axios.get(`/api/admin/campaigns?page[number]=${state.campaigns.meta.current_page - 1}`)
                    .then((response) => {
                        state.campaigns = response.data
                    })
            }
        },
        page({ state }, number) {
            if (state.campaigns != null && number > 0 && number <= state.campaigns.meta.last_page) {
                axios.get(`/api/admin/campaigns?page[number]=${number}`)
                    .then((response) => {
                        state.campaigns = response.data
                    })
            }
        },
        next({ state }) {
            if (state.campaigns != null && state.campaigns.meta.current_page < state.campaigns.meta.last_page) {
                axios.get(`/api/admin/campaigns?page[number]=${state.campaigns.meta.current_page + 1}`)
                    .then((response) => {
                        state.campaigns = response.data
                    })
            }
        },
        load({ state }) {
            return axios.get(`/api/admin/campaigns`)
                .then((response) => {
                    state.campaigns = response.data
                })
        },
        loadForUser({ state }, userId) {
            if (state.userCampaigns.hasOwnProperty(userId)) {
                return Promise.resolve(state.userCampaigns[userId])
            }
            return axios.get(`/api/admin/campaigns?filters[user_id]=${userId}`)
                .then((response) => {
                    state.userCampaigns[userId] = response.data.data
                    return response.data.data
                })
        },
        find({ state }, id) {
            if ((
                state.campaigns?.data || []
            ).length > 0) {
                const campaign = state.campaigns.data.find((item) => item.id === id)
                if (campaign) {
                    return Promise.resolve(campaign)
                }
            }
            return axios.get(`/api/admin/campaigns/${id}`)
                .then((response) => {
                    return response.data
                })
        },
        store({ dispatch }, data) {
            return axios.post(`/api/admin/campaigns`, data.campaign)
                .then(() => {
                    dispatch('Messages/success', 'Campaign saved!', { root: true })
                })
        },
        update({ dispatch }, data) {
            return axios.put(`/api/admin/campaigns/${data.id}`, data.campaign)
                .then(() => {
                    dispatch('Messages/success', 'Campaign saved!', { root: true })
                })
        },
        destroy({ dispatch }, campaign) {
            return axios.delete(`/api/admin/spells/${campaign.id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            dispatch('Messages/success', 'Campaign successfully deleted!', { root: true })
                        })
                })
        }
    },
    getters: {
        userCampaigns: (state) => userId => {
            return state.userCampaigns[userId] || []
        }
    }
}