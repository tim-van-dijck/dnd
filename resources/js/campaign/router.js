import { createRouter, createWebHashHistory } from 'vue-router'
import NotFound from '../components/NotFound'
import Dashboard from './components/dashboard'
import { CharacterRoutes } from './routes/characters'
import { InventoryRoutes } from './routes/inventory'
import { JournalRoutes } from './routes/journal'
import { LocationRoutes } from './routes/locations'
import { NoteRoutes } from './routes/notes'
import { QuestRoutes } from './routes/quests'
import { RoleRoutes } from './routes/roles'
import { UserRoutes } from './routes/users'

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
        path: '/:pathMatch(.*)*',
        component: NotFound
    }
]

const router = createRouter({ routes, history: createWebHashHistory() })
router.beforeEach((to, from, next) => {
    if (to.hasOwnProperty('meta') && to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title
    }
    next()
})
export default router