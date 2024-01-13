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
            to: (row) => (
                { name: 'campaign-edit', params: { id: row.id } }
            )
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
            formatRaw(admins) {
                const template = []
                for (const admin of admins) {
                    template.push(`<a href="/admin#/users/${admin.id}">${admin.name}</a>`)
                }
                return template.join('<br>')
            }
        }
    ]
}