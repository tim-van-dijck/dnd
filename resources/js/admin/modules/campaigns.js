export const Campaigns = {
    namespaced: true,
    state: {
        campaigns: null,
        userCampaigns: {},
        errors: {}
    },
    actions: {
        previous({commit,state}) {
            if (state.campaigns != null && state.campaigns.meta.current_page > 1) {
                axios.get(`/admin/campaigns?page[number]=${state.campaigns.meta.current_page - 1}`)
                    .then((response) => {
                        commit('SET_CAMPAIGNS', response.data);
                    })
            }
        },
        page({commit,state}, number) {
            if (state.campaigns != null && number > 0 && number <= state.campaigns.meta.last_page)
                axios.get(`/admin/campaigns?page[number]=${number}`)
                    .then((response) => {
                        commit('SET_CAMPAIGNS', response.data);
                    })
        },
        next({commit,state}) {
            if (state.campaigns != null && state.campaigns.meta.current_page < state.campaigns.meta.last_page) {
                axios.get(`/admin/campaigns?page[number]=${state.campaigns.meta.current_page + 1}`)
                    .then((response) => {
                        commit('SET_CAMPAIGNS', response.data);
                    })
            }
        },
        load({commit}) {
            return axios.get(`/admin/campaigns`)
                .then((response) => {
                    commit('SET_CAMPAIGNS', response.data)
                });
        },
        loadForUser({commit, state}, userId) {
            if (state.userCampaigns.hasOwnProperty(userId)) {
                return Promise.resolve(state.userCampaigns[userId]);
            }
            return axios.get(`/admin/campaigns?filters[user_id]=${userId}`)
                .then((response) => {
                    commit('SET_USER_CAMPAIGNS', {userId, campaigns: response.data.data})
                    return response.data.data;
                });
        },
        find({state}, id) {
            if ((state.campaigns?.data || []).length > 0) {
                let campaign = state.campaigns.data.find((item) => item.id === id);
                if (campaign) {
                    return Promise.resolve(campaign);
                }
            }
            return axios.get(`/admin/campaigns/${id}`)
                .then((response) => {
                    return response.data;
                });
        },
        store({dispatch, commit}, data) {
            return axios.post(`/admin/campaigns`, data.campaign)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Campaign saved!', {root: true});
                });
        },
        update({dispatch, commit}, data) {
            return axios.put(`/admin/campaigns/${data.id}`, data.campaign)
                .then(() => {
                    commit('SET_ERRORS', {});
                    dispatch('Messages/success', 'Campaign saved!', {root: true});
                });
        },
        destroy({dispatch}, campaign) {
            return axios.delete(`/admin/spells/${campaign.id}`)
                .then(() => {
                    dispatch('load')
                        .then(() => {
                            dispatch('Messages/success', 'Campaign successfully deleted!', {root: true});
                        });
                })
        }
    },
    mutations: {
        SET_CAMPAIGNS(state, campaigns) {
            state.campaigns = campaigns;
        },
        SET_USER_CAMPAIGNS(state, data) {
            state.userCampaigns[data.userId] = data.campaigns;
        }
    },
    getters: {
        userCampaigns: (state) => userId => {
            return state.userCampaigns[userId] || [];
        }
    }
}