export const ui = {
  actions: [
    {
      name: 'destroy',
      icon: 'trash',
      classes: 'uk-text-danger'
    },
    {
      name: 'view',
      icon: 'eye',
      to: (row) => `/users/${row.id}`
    }
  ],
  columns: [
    {
      name: 'name',
      title: 'Name'
    },
    {
      name: 'email',
      title: 'Email'
    }
  ]
}