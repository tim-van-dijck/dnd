import CampaignOverview from './components/campaigns/campaign-overview';
import CampaignForm from './components/campaigns/campaign-form';

import VueRouter from "vue-router";
import NotFound from "../components/NotFound";
import {RaceRoutes} from "./routes/races";
import {UserRoutes} from "./routes/users";
import {SpellRoutes} from "./routes/spells";
import {CampaignRoutes} from "./routes/campaigns";

const routes = [
    {
        path: '/',
        redirect: '/campaigns'
    },

    ...CampaignRoutes,
    ...UserRoutes,
    ...RaceRoutes,
    ...SpellRoutes,

    {
        path: '*',
        component: NotFound
    }
];

const router = new VueRouter({routes});
router.beforeEach((to, from, next) => {
    if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title;
    }
    next();
});
export default router;