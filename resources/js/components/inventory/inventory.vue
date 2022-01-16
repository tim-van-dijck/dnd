<template>
    <div>
        <h1>
            <router-link class="uk-link-text" :to="{name: 'inventories'}"><i class="fas fa-chevron-left"></i></router-link>
            <template v-if="inventory && inventory.hasOwnProperty('character')">
                Inventory for
                <router-link :to="{name: 'pc-detail', params: {id: inventory.character.id}}"
                             class="uk-link-text header-link">
                    {{ inventory.character.name }}
                </router-link>
            </template>
            <template v-else>
                {{ inventory && inventory.character_id === null ? 'Party inventory' : '' }}
            </template>
        </h1>
        <div v-if="inventory" class="uk-section uk-section-default">
            <div class="uk-container padded">
                <div class="uk-margin-bottom uk-width-4-6@l uk-width-1-1@m" uk-grid>
                    <div class="uk-width-1-6@s" :key="coin" v-for="coin in coins">
                        <template v-if="editMode">
                            <label :for="coin" class="uk-form-label uk-align-center">
                                <i :title="coin.replace(/^\w/, (c) => c.toUpperCase())"
                                   :class="`fas fa-coins fa-2x currency-${coin}`" />
                            </label>
                            <div class="form-controls">
                                <input :id="coin" :name="coin" type="number" min="0"
                                       class="uk-input uk-form-width-small uk-margin uk-margin-remove@m"
                                       v-model="inventory[coin]">
                            </div>
                        </template>
                        <template v-else>
                            <i :title="coin.replace(/^\w/, (c) => c.toUpperCase())"
                               :class="`fas fa-coins fa-2x currency-${coin}`" />
                            {{ inventory[coin] || 0 }}
                        </template>
                    </div>
                    <div class="uk-width-expand">
                        <template v-if="editMode">
                            <button class="uk-button uk-button-primary" @click.prevent="savePurse">
                                Save changes
                            </button>
                            <button class="uk-button uk-button-text" @click.prevent="cancel">
                                Cancel
                            </button>
                        </template>
                        <button v-else class="uk-button uk-button-text" @click.prevent="editMode = true">
                            <i class="fas fa-edit" /> Edit purse
                        </button>
                    </div>
                </div>
                <div class="uk-width">
                    <ul uk-tab>
                        <li :key="tab.key" v-for="tab in tabs" :class="{'uk-active': active === tab.key}">
                            <a :href="`#${tab.key}`" @click.prevent="active = tab.key">{{ tab.title }}</a>
                        </li>
                    </ul>
                    <inventory-tab-weapons v-show="active === 'weapons'" :inventory-id="id" :items="weapons" />
                    <inventory-tab-armor v-show="active === 'armor'" :inventory-id="id" :items="armor" />
                    <inventory-tab-potions v-show="active === 'potions'" :inventory-id="id" :items="potions" />
                </div>
            </div>
        </div>
        <div v-else class="uk-section uk-section-default">
            <p class="uk-text-center">
                <i class="fas fa-sync fa-spin fa-2x"></i>
            </p>
        </div>
    </div>
</template>

<script>
import InventoryTabWeapons from "./tabs/weapons/inventory-tab-weapons";
import InventoryTabArmor from "./tabs/armor/inventory-tab-armor";
import {mapState} from "vuex";
import InventoryTabPotions from "./tabs/potions/inventory-tab-potions";

export default {
    name: "inventory",
    components: {InventoryTabPotions, InventoryTabArmor, InventoryTabWeapons},
    props: ['id'],
    mounted() {
        this.$store.dispatch('Inventory/find', this.id);
    },
    data() {
        return {
            active: 'weapons',
            editMode: false,
            input: {}
        }
    },
    methods: {
        cancel() {
            this.input = {}
            this.editMode = false;
        },
        savePurse() {
            this.$store.dispatch('Inventory/update', {id: this.id, input: this.input})
                .then(() => {
                    this.editMode = false;
                })
        }
    },
    computed: {
        ...mapState(['campaign']),
        inventory() {
            return this.$store.getters['Inventory/inventory'](this.id) || null;
        },
        coins() {
            let coins = ['platinum', 'gold', 'silver', 'copper'];
            if (this.campaign.use_electrum) {
                coins.splice(2, 0, 'electrum')
            }
            return coins
        },
        tabs() {
            return [
                {
                    key: 'weapons',
                    title: 'Weapons'
                },
                {
                    key: 'armor',
                    title: 'Armor'
                },
                {
                    key: 'potions',
                    title: 'Potions'
                },
                {
                    key: 'other',
                    title: 'Other'
                }
            ]
        },
        title() {
            let name = this.inventory?.character?.name;
            if (name != null) {
                return `Inventory for ${name}`
            } else {
                return this.inventory && this.inventory.character_id === null ? 'Party inventory' : ''
            }
        },
        weapons() {
            if (!this.inventory?.items) {
                return []
            }
            return this.inventory.items.filter(item => item.category === 'Weapons')
        },
        armor() {
            if (!this.inventory?.items) {
                return []
            }
            return this.inventory.items.filter(item => item.category === 'Armor')
        },
        potions() {
            if (!this.inventory?.items) {
                return []
            }
            return this.inventory.items.filter(item => item.category === 'Potions')
        },
        other() {
            if (!this.inventory?.items) {
                return []
            }
            return this.inventory.items.filter(item => !['Armor', 'Potions', 'Weapons'].includes(item.category))
        }
    },
    watch: {
        inventory: {
            deep: true,
            handler() {
                this.input = {
                    platinum: this.inventory.platinum,
                    gold: this.inventory.gold,
                    silver: this.inventory.silver,
                    copper: this.inventory.copper
                }
            }
        }
    }
}
</script>