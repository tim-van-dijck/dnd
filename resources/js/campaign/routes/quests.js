import QuestForm from '../components/quests/quest-form'
import QuestOverview from '../components/quests/quest-overview'
import Quest from '../components/quests/quest/quest'

export const QuestRoutes = [
    {
        path: '/quests',
        name: 'quests',
        props: true,
        component: QuestOverview
    },
    {
        path: '/quests/create',
        name: 'quest-create',
        props: true,
        component: QuestForm
    },
    {
        path: '/quests/:id',
        name: 'quest',
        props: true,
        component: Quest
    },
    {
        path: '/quests/:id/edit',
        name: 'quest-edit',
        props: true,
        component: QuestForm
    }
]