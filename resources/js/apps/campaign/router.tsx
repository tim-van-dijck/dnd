import { BaseRoute, IndexRoute, Route } from '@dnd/types'
import { Route as ReactRoute } from 'react-router-dom'
import NotFound from '../../components/views/NotFound'
import Dashboard from './components/Dashboard'
import { CharacterRoutes } from './routes/characters'
import { JournalRoutes } from './routes/journal'
import { LocationRoutes } from './routes/locations'
import { NoteRoutes } from './routes/notes'
import { QuestRoutes } from './routes/quests'
import { RoleRoutes } from './routes/roles'
import { UserRoutes } from './routes/users'

const routes: Route[] = [
  {
    path: '/',
    element: <Dashboard />
  },
  {
    path: '/dashboard',
    element: <Dashboard />
  },

  ...CharacterRoutes,
  ...LocationRoutes,
  ...QuestRoutes,
  ...JournalRoutes,
  ...NoteRoutes,
  ...UserRoutes,
  ...RoleRoutes,
  /*
    ...InventoryRoutes,
  */

  {
    path: '*',
    element: <NotFound />
  }
]

const router = {
  routes,
  get() {
    return routes.map(mapRoute)
  }
}

const mapRoute = (route: Route) => {
  return !route.index ? mapBaseRoute(route) : mapIndexRoute(route as IndexRoute)
}
const mapBaseRoute = (route: BaseRoute) => (
  <ReactRoute key={route.path}
              path={route.path}
              element={route.element}>
    {route.children?.map((sub) => mapRoute(sub))}
  </ReactRoute>
)
const mapIndexRoute = (route: IndexRoute, parent?: BaseRoute) => <ReactRoute key={`${parent ?
  parent.path + '-' :
  ''}index`} element={route.element} index />

export default router