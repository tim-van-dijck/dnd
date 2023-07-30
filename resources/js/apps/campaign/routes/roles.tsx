import RoleForm from '../components/roles/RoleForm'
import RoleOverview from '../components/roles/RoleOverview'

export const RoleRoutes = [
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