import { reactive } from 'vue'

export const useInventoryTabs = () =>
    reactive({
        active: 'weapons',
        setActive(tab) {
            this.active = tab
        },
        list: [
            {
                key: 'weapons',
                title: 'Weapons'
            },
            {
                key: 'armor',
                title: 'Armor'
            },
            {
                key: 'potions',
                title: 'Potions'
            },
            {
                key: 'other',
                title: 'Other'
            }
        ]
    })