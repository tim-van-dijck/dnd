import { reactive } from 'vue'
import { useRouter } from 'vue-router'

export const useCampaignFormState = (store, messages) => {
    const router = useRouter()

    const state = reactive({
        campaign: null,
        errors: {},
        setCampaign(campaign) {
            this.campaign = campaign
        },
        save() {
            const { id, ...campaign } = this.campaign
            const promise = id ? store.update({ id: id || null, campaign }) : store.store(campaign)
            promise
                .then(() => router.push({ name: 'campaigns' }))
                .catch((error) => {
                    this.errors = error.response.data.errors
                    messages.error(error.response.data.message)
                })
        }
    })

    return { state }
}
