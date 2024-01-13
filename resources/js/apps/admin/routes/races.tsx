import RaceDetail from "../components/races/RaceDetail";
import RaceForm from '../components/races/RaceForm'
import RaceOverview from '../components/races/RaceOverview'

export const RaceRoutes = [
  {
    path: '/races',
    element: <RaceOverview />
  },
  {
    path: '/races/create',
    element: <RaceForm />
  },
  {
    path: '/races/:id/edit',
    element: <RaceForm />
  },
  {
    path: '/races/:id',
    element: <RaceDetail />
  }
]