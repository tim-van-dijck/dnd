import CampaignForm from "../components/campaigns/CampaignForm";
import CampaignOverview from "../components/campaigns/CampaignOverview";

export const CampaignRoutes = [
  {
    path: '/campaigns',
    element: <CampaignOverview />
  },
  {
    path: '/campaigns/:id/edit',
    element: <CampaignForm />
  }
]