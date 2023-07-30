<template>
    <div>
        <add-weapon-modal :inventory-id="state.inventoryId"/>
        <div class="uk-margin-top">
            <inventory-table type="weapons" :inventoryId="state.inventoryId" :columns="ui.columns" :items="state.items">
                <template #cell_properties="props">
                    <span v-for="property in props.column.format(props.item.properties)"
                          class="uk-label uk-margin-small-right"
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
import AddWeaponModal from './components/add-weapon-modal'
import ui from './inventory-tab-weapons.ui'

export default {
    name: 'inventory-tab-weapons',
    props: ['items', 'inventoryId'],
    setup(props) {
        return { state: { inventoryId: props.inventoryId, items: props.items }, ui }
    },
    components: { AddWeaponModal, InventoryTable }
}
</script>