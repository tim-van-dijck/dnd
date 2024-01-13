export const ui = {
  columns: [
    {
      title: 'Name',
      name: 'name'
    }
  ],
  actions: [
    {
      name: 'destroy',
      icon: 'trash',
      classes: 'uk-text-danger'
    },
    {
      name: 'edit',
      icon: 'edit',
      to: (note) => `/notes/${note.id}/edit`
    },
    {
      name: 'view',
      icon: 'eye',
      to: (note) => `/notes/${note.id}`
    }
  ]
}