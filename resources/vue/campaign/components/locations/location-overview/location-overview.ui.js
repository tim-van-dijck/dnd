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
            to: (location) => (
                { name: 'location-edit', params: { id: location.id } }
            )
        },
        {
            name: 'view',
            icon: 'eye',
            to: (location) => (
                { name: 'location', params: { id: location.id } }
            )
        }
    ],
    columns: [
        {
            name: 'name',
            title: 'Name'
        },
        {
            name: 'type',
            title: 'Type'
        },
        {
            name: 'location',
            title: 'Location',
            format: (location) => location ? location.name || 'N/A' : 'N/A'
        }
    ]
}