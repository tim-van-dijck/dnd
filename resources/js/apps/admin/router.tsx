import CampaignOverview from "./components/campaigns/CampaignOverview";
import { CampaignRoutes } from "./routes/campaigns";
import { RaceRoutes } from "./routes/races";
import { SpellRoutes } from "./routes/spells";
import { UserRoutes } from "./routes/users";

export default [
  {
    path: '/',
    element: <CampaignOverview />
  },
  ...CampaignRoutes,
  ...RaceRoutes,
  ...SpellRoutes,
  ...UserRoutes
]