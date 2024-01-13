import { Link } from "react-router-dom";

export const ui = {
  actions: [
    {
      name: 'destroy',
      icon: 'trash',
      classes: 'uk-text-danger'
    },
    {
      name: 'edit',
      icon: 'edit',
      to: (row) => `/campaigns/${row.id}/edit`
    },
    {
      name: 'view',
      icon: 'eye',
      href: (row) => `/campaigns/${row.id}`
    }
  ],
  columns: [
    {
      name: 'name',
      title: 'Name'
    },
    {
      name: 'admins',
      title: 'Admins',
      format: (admins) => admins.map((admin) => <div key={admin.id}>
        <Link to={`/users/${admin.id}`}>{admin.name}</Link><br /></div>)
    }
  ]
}