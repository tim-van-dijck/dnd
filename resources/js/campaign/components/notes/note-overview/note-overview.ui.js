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
            to: (note) => (
                { name: 'note-edit', params: { id: note.id } }
            )
        },
        {
            name: 'view',
            icon: 'eye',
            to: (note) => (
                { name: 'note', params: { id: note.id } }
            )
        }
    ]
}