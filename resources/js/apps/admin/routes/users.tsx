import UserDetail from "../components/users/UserDetail";
import UserForm from "../components/users/UserForm";
import UserOverview from "../components/users/UserOverview";

export const UserRoutes = [
  {
    path: '/users',
    element: <UserOverview />
  },
  {
    path: '/users/create',
    element: <UserForm />
  },
  {
    path: '/users/:id/edit',
    element: <UserForm />
  },
  {
    path: '/users/:id',
    element: <UserDetail />
  }
]