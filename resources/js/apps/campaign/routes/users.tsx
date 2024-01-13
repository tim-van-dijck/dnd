import { Route } from '@dnd/types'
import UserOverview from '../components/Users/UserOverview'

export const UserRoutes: Route[] = [
  {
    path: '/users',
    element: <UserOverview />
  }
]