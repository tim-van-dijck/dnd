import {createRouter, createWebHistory} from "vue-router";
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
        path: '/:pathMatch(.*)*',
        component: NotFound
    }
];

const router = createRouter({routes, history: createWebHistory()});
router.beforeEach((to, from, next) => {
    if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title;
    }
    next();
});
export {router};