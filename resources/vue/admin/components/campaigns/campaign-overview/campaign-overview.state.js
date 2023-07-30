import { useModals } from '../../../modals'


export const useCampaignOverviewState = (store) => {
    const { confirmDelete } = useModals()
    return {
        destroy: (campaign) => {
            confirmDelete(
                'campaign',
                () => store.destroy(campaign).then(() => store.load())
            )
        }
    }
}

