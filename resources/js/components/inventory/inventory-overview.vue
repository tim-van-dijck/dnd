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
                            <p><em>{{ wealth(inventory) }}</em></p>
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
import {mapState} from "vuex";

export default {
    name: "inventory-overview",
    created() {
        this.$store.dispatch('Inventory/load');
    },
    methods: {
        wealth(inventory) {
            let fallback = 'There is no currency in this inventory.';
            if (!inventory) {
                return fallback;
            }

            let currency = [];
            for (let coin of ['platinum', 'electrum', 'gold', 'silver', 'copper']) {
                if ((inventory[coin] || 0) > 0) {
                    currency.push(`${inventory[coin]} ${coin.substr(0,1)}p`);
                }
            }

            return currency.length > 0 ? currency.join(' ') : fallback;
        }
    },
    computed: {
        ...mapState('Inventory', ['characterInventories'])
    }
}
</script>