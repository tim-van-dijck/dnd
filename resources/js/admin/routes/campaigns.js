import CampaignOverview from "../components/campaigns/campaign-overview";
import CampaignForm from "../components/campaigns/campaign-form";

export const CampaignRoutes = [
    {
        path: '/campaigns',
        name: 'campaigns',
        props: true,
        component: CampaignOverview
    },
    {
        path: '/campaigns/:id',
        name: 'campaign-edit',
        props: true,
        component: CampaignForm
    }
]