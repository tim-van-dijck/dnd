import UserOverview from "../components/users/user-overview";
import UserForm from "../components/users/user-form";

export const UserRoutes = [
    {
        path: '/users',
        name: 'users',
        props: true,
        component: UserOverview
    },
    {
        path: '/users/invite',
        name: 'user-invite',
        props: true,
        component: UserForm
    },
    {
        path: '/users/:id/edit',
        name: 'user-edit',
        props: true,
        component: UserForm
    }
]