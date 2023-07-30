import { computed } from 'vue'

export const useNavigation = (store) => {
    const logout = () => {
        axios.post('/logout')
            .then(() => {
                document.location.href = '/'
            })
    }

    const navigation = computed(() => {
        const nav = [
            {
                title: 'CAMPAIGN',
                items: [
                    {
                        name: 'character',
                        title: 'Characters',
                        to: { name: 'player-characters' },
                        icon: 'users'
                    },
                    {
                        name: 'location',
                        title: 'Locations',
                        to: { name: 'locations' },
                        icon: 'map-marked-alt'
                    },
                    {
                        name: 'quest',
                        title: 'Quests',
                        to: { name: 'quests' },
                        icon: 'exclamation'
                    },
                    {
                        name: 'journal',
                        title: 'Journal',
                        to: { name: 'journal' },
                        icon: 'book-open'
                    },
                    {
                        name: 'note',
                        title: 'Notes',
                        to: { name: 'notes' },
                        icon: 'file-alt'
                    },
                    {
                        name: 'inventory',
                        title: 'Inventory',
                        to: { name: 'inventories' },
                        icon: 'shopping-bag'
                    }
                ]
            },
            {
                title: 'PLATFORM',
                items: [
                    {
                        name: 'user',
                        title: 'Users',
                        to: { name: 'users' },
                        icon: 'users-cog'
                    },
                    {
                        name: 'role',
                        title: 'Campaign Roles',
                        to: { name: 'roles' },
                        icon: 'user-lock'
                    }
                ]
            }
        ]

        return nav.filter(section => {
            section.items = section.items.filter((item) => store.can('view', item.name))
            return section.items.length > 0
        })
    })

    return { logout, navigation }
}