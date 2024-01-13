import CampaignForm from '../components/campaigns/campaign-form'
import CampaignOverview from '../components/campaigns/campaign-overview'

export const CampaignRoutes = [
    {
        path: '/campaigns',
        name: 'campaigns',
        component: CampaignOverview
    },
    {
        path: '/campaigns/:id',
        name: 'campaign-edit',
        props: true,
        component: CampaignForm
    }
]