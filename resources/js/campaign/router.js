import Dashboard from './components/dashboard';
import {CharacterRoutes} from './routes/characters';
import {InventoryRoutes} from './routes/inventory';
import {JournalRoutes} from './routes/journal';
import {LocationRoutes} from './routes/locations';
import {NoteRoutes} from './routes/notes';
import {QuestRoutes} from './routes/quests';
import {RoleRoutes} from './routes/roles';
import {UserRoutes} from './routes/users';

import VueRouter from "vue-router";
import NotFound from "../components/NotFound";

const routes = [
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

const router = new VueRouter({routes});
router.beforeEach((to, from, next) => {
    if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title;
    }
    next();
});
export default router;