import { reactive } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore()
const router = useRouter()

export const state = reactive({
    campaign: null,
    errors: {},
    setCampaign(campaign) {
        this.campaign = campaign
    },
    save() {
        store.dispatch('Campaigns/update', { id: this.id, campaign: this.campaign })
            .then(() => {
                router.push({ name: 'campaigns' });
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                store.dispatch('Messages/error', error.response.data.message, { root: true })
            });
    }
})