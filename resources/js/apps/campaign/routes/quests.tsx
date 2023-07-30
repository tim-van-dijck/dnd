import QuestDetail from "../components/quests/QuestDetail";
import QuestForm from "../components/quests/QuestForm";
import QuestOverview from '../components/quests/QuestOverview'

export const QuestRoutes = [
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