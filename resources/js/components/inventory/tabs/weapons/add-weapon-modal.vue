<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #add-weapon-modal">
            <i class="fas fa-plus"></i> Add weapon
        </button>
        <div id="add-weapon-modal" uk-modal @beforehide="weaponId = 0; quantity = 1">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Add a weapon</h2>
                <form class="uk-margin" @submit.prevent="addWeapon">
                    <label class="uk-form-label" for="weapon-selection">Select</label>
                    <div class="uk-flex uk-flex-between">
                        <div class="uk-form-controls">
                            <select class="uk-select uk-width-auto"
                                    :class="{'uk-form-danger': errors.hasOwnProperty('id')}"
                                    id="weapon-selection" v-model="weaponId">
                                <option :value="0">- Choose a weapon -</option>
                                <optgroup v-for="(options, type) in weaponOptions" :label="type">
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
                <template v-if="selectedWeapon !== null">
                    <hr>
                    <div class="uk-margin">
                        <h3>{{ selectedWeapon.name }}</h3>
                        <p>
                            <span class="uk-label uk-margin-small-right"
                                  :class="{'uk-label-danger': selectedWeapon.properties.damage > 0, 'uk-label-warning': (selectedWeapon.properties.damage || 0) == 0}">
                                <template v-if="selectedWeapon.properties.damage > 0">
                                    Damage:
                                    {{ selectedWeapon.properties.damage}}{{ selectedWeapon.properties.damage_dice }} {{ selectedWeapon.properties.damage_type }}
                                </template>
                                <template v-else>No Damage</template>
                            </span>
                            <span v-for="property in formattedProperties" class="uk-label uk-margin-small-right">
                                {{ property }}
                            </span>
                        </p>
                        <p v-if="selectedWeapon.properties.special">{{ selectedWeapon.properties.special }}</p>
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
    name: "add-weapon-modal",
    created() {
        this.$store.dispatch('Inventory/loadItems', 'weapons')
    },
    data() {
        return {
            quantity: 1,
            weaponId: 0,
            errors: {}
        }
    },
    methods: {
        addWeapon() {
            this.$store.dispatch('Inventory/add', {inventoryId: this.inventoryId, item: this.selectedWeapon, quantity: this.quantity})
                .then(() => {
                    this.$set(this, 'errors', {});
                    UIKit.modal("#add-weapon-modal").hide();
                })
                .catch((exception) => {
                    this.$set(this, 'errors', exception.response.data.errors)
                });
        }
    },
    computed: {
        ...mapState('Inventory', ['items']),
        selectedWeapon() {
            if (this.weaponId <= 0) {
                return null;
            }
            return this.items?.weapons.find(weapon => weapon.id === this.weaponId)
        },
        formattedProperties() {
            if (!this.selectedWeapon) {
                return [];
            }
            const result = [];
            for (let property in this.selectedWeapon.properties) {
                const value = this.selectedWeapon.properties[property];
                switch (property) {
                    case 'damage':
                    case 'damage_dice':
                    case 'damage_type':
                        break;
                    case 'dual_wield':
                        result.push('Light');
                        break;
                    case 'two_handed':
                        result.push('Two-Handed');
                        break;
                    case 'range':
                        result.push(`Range (${value})`);
                        break;
                    case 'versatile':
                        result.push(`Versatile (${value})`);
                        break;
                    case 'special':
                        result.push('Special');
                        break;
                    default:
                        result.push(property.replace(/^\w/, (c) => c.toUpperCase()));
                        break;
                }
            }
            return result;
        },
        weaponOptions() {
            const weapons = this.items?.weapons || [];
            let grouped = {};
            for (let weapon of weapons) {
                if (!grouped.hasOwnProperty(weapon.type)) {
                    grouped[weapon.type] = [];
                }
                grouped[weapon.type].push(weapon);
            }
            return grouped;
        }
    }
}
</script>