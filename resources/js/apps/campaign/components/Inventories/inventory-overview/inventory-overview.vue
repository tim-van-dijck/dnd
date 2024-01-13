<template>
    <div id="Inventory">
        <h1>Inventory</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <div v-if="characterInventories !== null && characterInventories.length > 0"
                     v-for="inventory in characterInventories"
                     :key="inventory.id" class="uk-width uk-card uk-card-body uk-card-secondary">
                    <div class="uk-flex">
                        <div class="uk-width">
                            <h3 class="uk-card-title">
                                <router-link class="uk-link-heading uk-width uk-display-block"
                                             :to="{name: 'inventory', params: {id: inventory.id}}">
                                    {{ inventory.character.name }}
                                </router-link>
                            </h3>
                            <p><em class="uk-text-emphasis">{{ wealth(inventory) }}</em></p>
                        </div>
                        <div class="uk-flex uk-flex-between">
                            <router-link tag="button" class="uk-button uk-button-round uk-button-default"
                                         :to="{name: 'inventory', params: {id: inventory.id}}">
                                <i class="fas fa-eye"></i>
                            </router-link>
                        </div>
                    </div>
                </div>
                <p v-else class="uk-text-center">
                    <i v-if="characterInventories == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>You don't have access to any character inventories</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useInventoryStore } from '../../../stores/inventory'
import { useWealth } from './inventory-overview.state'

export default {
    name: 'inventory-overview',
    setup() {
        const store = useInventoryStore()
        const { characterInventories } = storeToRefs(store)
        const wealth = useWealth()

        onMounted(() => store.load())

        return { characterInventories, wealth }
    }
}
</script>