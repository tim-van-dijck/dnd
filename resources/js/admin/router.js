import { createRouter, createWebHistory } from 'vue-router'
import NotFound from '../components/NotFound'
import { CampaignRoutes } from './routes/campaigns'
import { ClassRoutes } from './routes/classes'
import { RaceRoutes } from './routes/races'
import { SpellRoutes } from './routes/spells'
import { UserRoutes } from './routes/users'

const routes = [
    ...CampaignRoutes,
    ...ClassRoutes,
    ...UserRoutes,
    ...RaceRoutes,
    ...SpellRoutes,

    {
        path: '/',
        redirect: { name: 'campaigns' }
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFound
    }
]


export const router = createRouter({ routes, history: createWebHistory('/admin/') })