<template>
    <div>
        <h1>
            <router-link class="uk-link-text" :to="{name: 'inventories'}"><i class="fas fa-chevron-left"></i>
            </router-link>
            <template v-if="state?.inventory?.character">
                Inventory for
                <router-link :to="{name: 'pc-detail', params: {id: state.inventory.character.id}}"
                             class="uk-link-text header-link">
                    {{ state.inventory.character.name }}
                </router-link>
            </template>
            <template v-else>
                {{ !state?.inventory?.character_id ? 'Party inventories' : '' }}
            </template>
        </h1>
        <div v-if="state.inventory" class="uk-section uk-section-default">
            <div class="uk-container padded">
                <div class="uk-margin-bottom uk-width-4-6@l uk-width-1-1@m" uk-grid>
                    <div class="uk-width-1-6@s" :key="coin" v-for="coin in ui.coins.value">
                        <template v-if="state.editMode">
                            <label :for="coin" class="uk-form-label uk-align-center">
                                <i :title="coin.replace(/^\w/, (c) => c.toUpperCase())"
                                   :class="`fas fa-coins fa-2x currency-${coin}`"/>
                            </label>
                            <div class="form-controls">
                                <input :id="coin" :name="coin" type="number" min="0"
                                       class="uk-input uk-form-width-small uk-margin uk-margin-remove@m"
                                       v-model="state.input[coin]">
                            </div>
                        </template>
                        <template v-else>
                            <i :title="coin.replace(/^\w/, (c) => c.toUpperCase())"
                               :class="`fas fa-coins fa-2x currency-${coin}`"/>
                            {{ state.inventory[coin] || 0 }}
                        </template>
                    </div>
                    <div class="uk-width-expand">
                        <template v-if="state.editMode">
                            <button class="uk-button uk-button-primary uk-margin-right"
                                    @click.prevent="state.savePurse">
                                Save changes
                            </button>
                            <button class="uk-button uk-button-text" @click.prevent="state.cancel">
                                Cancel
                            </button>
                        </template>
                        <button v-else class="uk-button uk-button-text" @click.prevent="() => state.setEditMode(true)">
                            <i class="fas fa-edit"/> Edit purse
                        </button>
                    </div>
                </div>
                <div class="uk-width">
                    <ul uk-tab>
                        <li :key="tab.key" v-for="tab in ui.tabs.list"
                            :class="{'uk-active': ui.tabs.active === tab.key}">
                            <a :href="`#${tab.key}`"
                               @click.prevent="() => ui.tabs.setActive(tab.key)">{{ tab.title }}</a>
                        </li>
                    </ul>
                    <inventory-tab-weapons v-show="ui.tabs.active === 'weapons'" :inventory-id="id"
                                           :items="ui.weapons"/>
                    <inventory-tab-armor v-show="ui.tabs.active === 'armor'" :inventory-id="id" :items="ui.armor"/>
                    <inventory-tab-potions v-show="ui.tabs.active === 'potions'" :inventory-id="id"
                                           :items="ui.potions"/>
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
import { onMounted } from 'vue'
import { useInventoryStore } from '../../../stores/inventory'
import InventoryTabArmor from './components/inventory-tab-armor'
import InventoryTabPotions from './components/inventory-tab-potions'
import InventoryTabWeapons from './components/inventory-tab-weapons'
import { useInventory } from './inventory.state'
import { useInventoryTabs } from './inventory.ui-logic'

export default {
    name: 'inventory',
    components: { InventoryTabPotions, InventoryTabArmor, InventoryTabWeapons },
    props: ['id'],
    setup(props) {
        const store = useInventoryStore()
        const { state, coins, weapons, armor, potions, other } = useInventory(store)
        const tabs = useInventoryTabs()

        onMounted(() => store.find(props.id).then((inventory) => state.setInventory(inventory)))

        return {
            state,
            ui: {
                tabs,
                armor,
                coins,
                other,
                potions,
                weapons
            }
        }
    }
}
</script>