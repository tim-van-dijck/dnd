<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #add-potion-modal">
            <i class="fas fa-plus"></i> Add potion
        </button>
        <div id="add-potion-modal" uk-modal @beforehide="potionId = 0; quantity = 1">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add a potion</h2>
                <form class="uk-margin" @submit.prevent="addPotion">
                    <label class="uk-form-label" for="potion-selection">Select</label>
                    <div class="uk-flex uk-flex-between">
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-auto"
                                    :class="{'uk-form-danger': errors.hasOwnProperty('id')}"
                                    id="potion-selection" v-model="potionId">
                                <option :value="0">- Choose a potion -</option>
                                <optgroup v-for="(options, type) in potionOptions" :label="type">
                                    <option v-for="option in options" :value="option.id">{{ option.name }}</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="uk-form-controls">
                            <input class="uk-input uk-width-small"
                                   :class="{'uk-form-danger': errors.hasOwnProperty('quantity')}"
                                   id="quantity-selection" type="number" v-model="quantity">
                        </div>
                        <button type="submit" class="uk-button uk-button-primary">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </form>
                <template v-if="selectedPotion !== null">
                    <hr>
                    <div class="uk-margin">
                        <h3>{{ selectedPotion.name }}</h3>
                        <p>{{ selectedPotion.description }}</p>
                    </div>
                </template>
                <button class="uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import UIKit from "uikit";

export default {
    props: ['inventoryId'],
    name: "add-potion-modal",
    created() {
        this.$store.dispatch('Inventory/loadItems', 'potions')
    },
    data() {
        return {
            quantity: 1,
            potionId: 0,
            errors: {}
        }
    },
    methods: {
        addPotion() {
            this.$store.dispatch('Inventory/add', {inventoryId: this.inventoryId, item: this.selectedPotion, quantity: this.quantity})
                .then(() => {
                    this.$set(this, 'errors', {});
                    UIKit.modal("#add-potion-modal").hide();
                })
                .catch((exception) => {
                    this.$set(this, 'errors', exception.response.data.errors)
                });
        }
    },
    computed: {
        ...mapState('Inventory', ['items']),
        selectedPotion() {
            if (this.potionId <= 0) {
                return null;
            }
            return this.items?.potions.find(potion => potion.id === this.potionId)
        },
        potionOptions() {
            const potionOptions = this.items?.potions || [];
            let grouped = {
                Potions: [],
                Poison: [],
                Miscellaneous: []
            };
            for (let potion of potionOptions) {
                if (!grouped.hasOwnProperty(potion.type)) {
                    grouped[potion.type] = [];
                }
                grouped[potion.type].push(potion);
            }
            return grouped;
        }
    }
}
</script>