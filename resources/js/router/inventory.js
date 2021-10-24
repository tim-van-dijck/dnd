import Inventory from "../components/inventory/inventory";
import InventoryOverview from "../components/inventory/inventory-overview";

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