import { Route } from '@dnd/types'
import RoleForm from '../components/Roles/RoleForm'
import RoleOverview from '../components/Roles/RoleOverview'

export const RoleRoutes: Route[] = [
  {
    path: '/roles',
    element: <RoleOverview />
  },
  {
    path: '/roles/create',
    element: <RoleForm />
  },
  {
    path: '/roles/:id/edit',
    element: <RoleForm />
  }
]