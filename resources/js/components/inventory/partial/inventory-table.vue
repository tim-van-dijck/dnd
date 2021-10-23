<template>
    <div>
        <table class="uk-table uk-table-divider" v-if="items.length > 0">
            <thead>
                <th></th>
                <th v-for="column in columns">{{ column.label }}</th>
            </thead>
            <tbody>
                <tr :key="item.id" v-for="item in items" :title="item.description">
                    <td>
                        <ul class="uk-iconnav">
                            <li>
                                <a href="#" @click.prevent="confirmRemove(item)">
                                    <i class="fas fa-trash uk-text-danger"></i>
                                </a>
                            </li>
                            <li v-for="action in actions">
                                <a href="#" @click.prevent="$emit(action.name, item)">
                                    <i :class="`fas fa-${action.icon}`"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                    <td :key="column.name" v-for="column in columns">
                        <slot :name="`cell_${column.name}`" :column="column" :item="item">
                            {{ format(item, column) }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else class="uk-text-center">
            <p v-if="items === null"><i class="fas fa-sync fa-spin fa-2x"></i></p>
            <div v-else class="uk-alert-warning" uk-alert>
                <p>No items of this kind in inventory.</p>
            </div>
        </div>


        <div :id="`remove-item-modal-${type}`" uk-modal
             @shown="document.getElementById(`remove-item-amount-selection-${type}`).focus()"
             @beforehide="selected = null; removeQuantity = 1">
            <div class="uk-modal-dialog uk-modal-body">
                <template v-if="selected !== null">
                    <h2 class="uk-modal-title">Remove {{ selected.name }}?</h2>
                    <p>How many items would you like to remove?</p>
                    <form class="uk-margin" @submit.prevent="remove">
                        <div class="uk-flex uk-flex-between">
                            <div class="uk-form-controls">
                                <input class="uk-input"
                                       :class="{'uk-form-danger': removeQuantity < 1 && removeQuantity > selected.quantity}"
                                       :id="`remove-item-amount-selection-${type}`" min="1" :max="selected.quantity" type="number" v-model="removeQuantity">
                            </div>
                            <button type="submit" class="uk-button uk-button-danger">Remove</button>
                        </div>
                    </form>
                </template>
                <button class="uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import UIKit from "uikit"

export default {
    name: "inventory-table",
    props: ['inventoryId', 'items', 'columns', 'type', 'actions'],
    data() {
        return {
            selected: null,
            removeQuantity: 1
        }
    },
    methods: {
        format(item, column) {
            let value =  column.name.split('.')
                .reduce((currentProp, segment) => {
                    if (!currentProp || !currentProp.hasOwnProperty(segment)) {
                        return null;
                    }
                    return currentProp[segment];
                }, item);
            if (column.hasOwnProperty('format') && typeof column.format === 'function') {
                return column.format(value, item);
            }
            return value;
        },
        confirmRemove(item) {
            this.$set(this, 'selected', item);
            UIKit.modal(`#remove-item-modal-${this.type}`).show();
        },
        remove() {
            const payload = {
                inventoryId: this.inventoryId,
                id: this.selected.id,
                quantity: this.removeQuantity
            }

            this.$store.dispatch('Inventory/remove', payload)
                .then(() => {
                    UIKit.modal(`#remove-item-modal-${this.type}`).hide();
                })
        }
    }
}
</script>