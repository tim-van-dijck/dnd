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
      to: (race) => `/races/${race.id}/edit`
    },
    {
      name: 'view',
      icon: 'eye',
      to: (race) => `/races/${race.id}`
    }
  ],
  columns: [
    {
      name: 'name',
      title: 'Name'
    },
    {
      name: 'size',
      title: 'Size'
    },
    {
      name: 'speed',
      title: 'Speed',
      format: (speed) => `${speed}ft.`
    }
  ]
}