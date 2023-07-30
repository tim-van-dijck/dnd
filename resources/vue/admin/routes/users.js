import UserDetail from '../components/users/user-detail'
import UserForm from '../components/users/user-form'
import UserOverview from '../components/users/user-overview'

export const UserRoutes = [
    {
        path: '/users',
        name: 'users',
        props: true,
        component: UserOverview
    },
    {
        path: '/users/:id',
        name: 'user',
        props: true,
        component: UserDetail
    },
    {
        path: '/users/:id/edit',
        name: 'user-edit',
        props: true,
        component: UserForm
    }
]