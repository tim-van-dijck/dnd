<template>
    <div>
        <add-potion-modal :inventory-id="inventoryId" />
        <inventory-table type="armor"
                         :inventoryId="inventoryId"
                         :columns="columns"
                         :actions="actions"
                         :items="items"
                         @info="info" />
    </div>
</template>

<script>
import InventoryTable from "../../partial/inventory-table";
import AddPotionModal from "./add-potion-modal";
import UIKit from "uikit";

export default {
    name: "inventory-tab-potions",
    props: ['items', 'inventoryId'],
    components: {AddPotionModal, InventoryTable},
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
                    name: 'weight',
                    label: 'Weight',
                    format(weight, item) {
                        return `${weight * item.quantity}lbs`
                    }
                }
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