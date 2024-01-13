<template>
    <div>
        <add-potion-modal :inventory-id="state.inventoryId"/>
        <div class="uk-margin-top">
            <inventory-table type="potions"
                             :inventoryId="state.inventoryId"
                             :columns="ui.columns"
                             :actions="ui.actions"
                             :items="state.items"
                             @info="ui.info"/>
        </div>
    </div>
</template>

<script>
import InventoryTable from '../inventory-table'
import AddPotionModal from './components/add-potion-modal'
import { ui } from './inventory-tab-potions.ui'

export default {
    name: 'inventory-tab-potions',
    props: ['items', 'inventoryId'],
    components: { AddPotionModal, InventoryTable },
    setup(props) {
        return {
            state: {
                inventoryId: props.inventoryId,
                items: props.items
            },
            ui
        }
    }
}
</script>