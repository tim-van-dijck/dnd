import CampaignOverview from './components/campaigns/campaign-overview';
import CampaignForm from './components/campaigns/campaign-form';

import UserOverview from './components/users/user-overview';
import UserDetail from './components/users/user-detail';
import UserForm from './components/users/user-form';

import SpellOverview from "./components/spells/spell-overview";
import SpellForm from "./components/spells/spell-form";

import VueRouter from "vue-router";
import NotFound from "../components/NotFound";

const routes = [
    {
        path: '/',
        redirect: 'campaigns'
    },

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
    },

    {
        path: '/users',
        name: 'users',
        props: true,
        component: UserOverview
    },
    {
        path: '/users/:id',
        name: 'user',
        props: true,
        component: UserDetail
    },
    {
        path: '/users/:id/edit',
        name: 'user-edit',
        props: true,
        component: UserForm
    },

    {
        path: '/spells',
        name: 'spells',
        props: true,
        component: SpellOverview
    },
    {
        path: '/spells/create',
        name: 'spell-create',
        props: true,
        component: SpellForm
    },
    {
        path: '/spells/:id',
        name: 'spell-edit',
        props: true,
        component: SpellForm
    },

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