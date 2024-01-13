import Inventory from '../components/inventories/inventory'
import InventoryOverview from '../components/inventories/inventory-overview'

export const InventoryRoutes = [
    {
        path: '/inventories',
        name: 'inventories',
        props: true,
        component: InventoryOverview
    },
    {
        path: '/inventories/:id',
        name: 'inventory',
        props: true,
        component: Inventory
    }
]