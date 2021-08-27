import Dashboard from './components/dashboard';
import {JournalRoutes} from './router/journal';
import {CharacterRoutes} from './router/characters';
import {LocationRoutes} from './router/locations';
import {NoteRoutes} from './router/notes';
import {QuestRoutes} from './router/quests';
import {RoleRoutes} from './router/roles';
import {UserRoutes} from './router/users';

import VueRouter from "vue-router";
import ComingSoon from "./components/ComingSoon";

let routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },

    ...CharacterRoutes,
    ...JournalRoutes,
    ...LocationRoutes,
    ...NoteRoutes,
    ...QuestRoutes,
    ...RoleRoutes,
    ...UserRoutes,

    {
        path: '/inventory',
        name: 'inventory',
        props: true,
        component: ComingSoon
    },
];

let router = new VueRouter({routes});
router.beforeEach((to, from, next) => {
    if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title;
    }
    next();
});
export default router;