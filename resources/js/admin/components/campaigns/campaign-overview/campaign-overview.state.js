import {confirmDelete} from "../../../modals";
import {useStore} from "vuex";

const store = useStore()

export const state = {
    campaigns: store.Campaigns.state.campaigns,
    destroy: (campaign) => {
        confirmDelete(
            'campaign',
            () => store.dispatch('Campaigns/destroy', campaign).then(() => store.dispatch('Campaigns/load'))
        )
    }
}