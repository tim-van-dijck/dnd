import { Route } from '@dnd/types'
import QuestDetail from '../components/Quests/QuestDetail'
import QuestForm from '../components/Quests/QuestForm'
import QuestOverview from '../components/Quests/QuestOverview'

export const QuestRoutes: Route[] = [
  {
    path: '/quests',
    element: <QuestOverview />
  },
  {
    path: '/quests/create',
    element: <QuestForm />
  },
  {
    path: '/quests/:id',
    element: <QuestDetail />
  },
  {
    path: '/quests/:id/edit',
    element: <QuestForm />
  }
]