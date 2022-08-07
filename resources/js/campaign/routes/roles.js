import RoleForm from '../components/roles/role-form'
import RoleOverview from '../components/roles/role-overview'

export const RoleRoutes = [
    {
        path: '/roles',
        name: 'roles',
        props: true,
        component: RoleOverview
    },
    {
        path: '/roles/create',
        name: 'role-create',
        props: true,
        component: RoleForm
    },
    {
        path: '/roles/:id/edit',
        name: 'role-edit',
        props: true,
        component: RoleForm
    }
]