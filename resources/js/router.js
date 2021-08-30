import Dashboard from './components/dashboard';
import {CharacterRoutes} from './router/characters';
import {InventoryRoutes} from './router/inventory';
import {JournalRoutes} from './router/journal';
import {LocationRoutes} from './router/locations';
import {NoteRoutes} from './router/notes';
import {QuestRoutes} from './router/quests';
import {RoleRoutes} from './router/roles';
import {UserRoutes} from './router/users';

import VueRouter from "vue-router";
import NotFound from "./components/NotFound";

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
    ...InventoryRoutes,

    {
        path: '*',
        component: NotFound
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