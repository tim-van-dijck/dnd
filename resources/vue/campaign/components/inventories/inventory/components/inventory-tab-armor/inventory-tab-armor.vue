<template>
    <div>
        <add-armor-modal :inventory-id="state.inventoryId"/>
        <div class="uk-margin-top">
            <inventory-table type="armor" :inventoryId="state.inventoryId" :columns="ui.columns" :actions="ui.actions"
                             :items="state.items"
                             @info="ui.info">
                <template #cell_properties="props">
                    <span v-for="property in props.column.format(props.item.properties)"
                          class="uk-label uk-margin-small-right" :class="`uk-label-${property.type || 'default'}`"
                          :title="property.title || ''">
                        {{ property.label }}
                    </span>
                </template>
            </inventory-table>
        </div>
    </div>
</template>

<script>
import InventoryTable from '../inventory-table'
import AddArmorModal from './components/add-armor-modal'
import { ui } from './inventory-tab-armor.ui'

export default {
    name: 'inventory-tab-armor',
    props: ['items', 'inventoryId'],
    components: { AddArmorModal, InventoryTable },
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