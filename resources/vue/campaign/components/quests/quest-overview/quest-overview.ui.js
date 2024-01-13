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
            to: (quest) => (
                { name: 'quest-edit', params: { id: quest.id } }
            )
        },
        {
            name: 'view',
            icon: 'eye',
            to: (quest) => (
                { name: 'quest', params: { id: quest.id } }
            )
        }
    ],
    columns: [
        { title: 'Title', name: 'title' },
        {
            title: 'Completion',
            name: 'objectives',
            format: (objectives) => `${objectives.filter((item) => item.status == 1).length}/${objectives.length}`
        },
        {
            title: 'Location',
            name: 'location',
            format: (location) => location || 'Not specified'
        }
    ]
}