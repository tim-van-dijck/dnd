export const ui = {
    actions: [
        {
            name: 'destroy',
            title: 'Delete character',
            icon: 'trash',
            classes: 'uk-text-danger'
        },
        {
            name: 'edit',
            title: 'Edit character',
            icon: 'edit',
            to: (character) => (
                { name: 'pc-edit', params: { id: character.id } }
            )
        },
        {
            name: 'view',
            title: 'Go to player-character',
            icon: 'eye',
            to: (character) => (
                { name: 'pc-detail', params: { id: character.id } }
            )
        },
        {
            name: 'sheet',
            title: 'Download character sheet',
            href: (row) => `/campaign/characters/${row.id}/sheet`,
            icon: 'file',
            newTab: true
        },
        {
            name: 'inventory',
            title: 'Go to inventories',
            icon: 'shopping-bag',
            to: (character) => (
                { name: 'inventory', params: { id: character.info.inventory_id } }
            )
        }
    ],
    columns: [
        {
            title: 'Name',
            name: 'info.name'
        },
        {
            title: 'Race',
            name: 'race',
            format(race) {
                let value = race.name
                if (race.subrace) {
                    value += ` (${race.subrace.name})`
                }
                return value
            }
        },
        {
            title: 'Class',
            name: 'classes',
            format(classes) {
                if (Array.isArray(classes)) {
                    if (classes.length === 1) {
                        return classes[0].name
                    } else {
                        const classNames = []
                        for (const charClass of classes) {
                            classNames.push(charClass.name)
                        }
                        if (classNames.length > 0) {
                            return `Multiclass: ${classNames.join(' - ')}`
                        }
                    }
                }
                return 'N/A'
            }
        },
        {
            title: 'Level',
            name: 'classes',
            format(classes) {
                let level = 0
                for (let charClass of classes) {
                    level += parseInt(charClass.level)
                }
                return level
            }
        },
        {
            name: 'owner',
            title: 'Owner',
            format(owner) {
                return owner || 'N/A'
            }
        }
    ]
}