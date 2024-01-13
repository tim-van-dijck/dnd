import { reactive } from 'vue'

export const useUserDetailState = () => {
    const state = reactive({
        user: null,
        setUser(user) {
            this.user = user
        },
        campaigns: null,
        setCampaigns(campaigns) {
            this.campaigns = campaigns
        }
    })

    return { state }
}