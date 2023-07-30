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
            to: (race) => (
                { name: 'race-edit', params: { id: race.id } }
            )
        },
        {
            name: 'view',
            icon: 'eye',
            to: (race) => (
                { name: 'race', params: { id: race.id } }
            )
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