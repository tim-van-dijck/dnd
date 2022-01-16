<template>
    <div>
        <add-armor-modal :inventory-id="inventoryId"></add-armor-modal>
        <inventory-table type="armor" :inventoryId="inventoryId" :columns="columns" :actions="actions" :items="items"
            @info="info">
            <template #cell_properties="props">
                <span v-for="property in props.column.format(props.item.properties)"
                      class="uk-label uk-margin-small-right" :class="`uk-label-${property.type || 'default'}`"
                      :title="property.title || ''">
                    {{ property.label }}
                </span>
            </template>
        </inventory-table>
    </div>
</template>

<script>
import InventoryTable from "../../partial/inventory-table";
import AddArmorModal from "./add-armor-modal";
import UIKit from "uikit"

export default {
    name: "inventory-tab-armor",
    props: ['items', 'inventoryId'],
    components: {AddArmorModal, InventoryTable},
    data() {
        return {
            actions: [
                {
                    name: 'info',
                    icon: 'info-circle',
                }
            ],
            columns: [
                {
                    name: 'name',
                    label: 'Name',
                    format(name, item) {
                        return `${name} ${item.equipped ? ' (equipped)' : ''}`
                    }
                },
                {
                    name: 'quantity',
                    label: '#'
                },
                {
                    name: 'properties.ac',
                    label: 'Armor Class (AC)',
                    format(ac, item) {
                        if (item.name === 'Shield') {
                            return '+2'
                        }
                        return `${ac}${item.properties.add_dex ? ' + Dex modifier (max. 2)' : ''}`
                    }
                },
                {
                    name: 'properties.strength',
                    label: 'Min. Strength',
                    format(strength) {
                        return strength || 'N/A'
                    }
                },
                {
                    name: 'weight',
                    label: 'Weight',
                    format(weight, item) {
                        return `${weight * item.quantity}lbs`
                    }
                },
                {
                    name: 'properties',
                    label: 'Properties',
                    format(properties) {
                        let result = [];
                        for (let key in properties) {
                            switch (key) {
                                case 'stealth_disadvantage':
                                    const title = properties[key] ? 'Disadvantage on Stealth' : 'No disadvantage on Stealth'
                                    result.push({label: 'Stealth', type: properties[key] ? 'danger' : 'success', title});
                                    break;
                                case 'don':
                                    result.push({label: `Don: ${properties.don}`});
                                    break;
                                case 'doff':
                                    result.push({label: `Don: ${properties.doff}`});
                                    break;
                            }
                        }
                        return result;
                    }
                },
            ]
        }
    },
    methods: {
        info(item) {
            UIKit.modal.dialog(`<div class="uk-modal-body"><h2>${item.name}</h2><div>${item.description}</div></div>`);
        }
    }
}
</script>