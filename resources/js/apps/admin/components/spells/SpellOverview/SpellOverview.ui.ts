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
      to: (row) => `/spells/${row.id}/edit`
    },
    {
      name: 'view',
      icon: 'eye',
      to: (row) => `/spells/${row.id}`
    }
  ],
  columns: [
    {
      name: 'name',
      title: 'Name'
    },
    {
      name: 'level',
      title: 'Level',
      format(level) {
        switch (level) {
          case 0:
            return 'Cantrip';
          case 1:
            return '1st level';
          case 2:
            return '2nd level';
          case 3:
            return '3rd level';
          default:
            return `${level}th level`;
        }
      }
    },
    {
      name: 'school',
      title: 'School',
    },
  ]
}