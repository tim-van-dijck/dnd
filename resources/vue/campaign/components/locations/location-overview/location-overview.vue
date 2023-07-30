<template>
    <div id="locations">
        <h1>Locations</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link v-if="can('create', 'location')"
                             class="uk-button uk-button-primary" :to="{name: 'location-create'}">
                    <i class="fas fa-plus"></i> Add location
                </router-link>
                <paginated-table v-if="locations != null && locations.data.length > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :store="store"
                                 :records="locations"
                                 @destroy="state.destroy"/>
                <p v-else class="uk-text-center">
                    <i v-if="locations == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No locations found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useLocationStore } from '@campaign/stores/locations'
import { useMainStore } from '@campaign/stores/main'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useLocationOverviewState } from './location-overview.state'
import { ui } from './location-overview.ui'

export default {
    name: 'location-overview',
    components: { PaginatedTable },
    setup() {
        const store = useLocationStore()
        const main = useMainStore()
        const { locations } = storeToRefs(store)
        const state = useLocationOverviewState(store)

        onMounted(() => store.load())

        return { can: main.can, locations, state, store, ui }
    }
}
</script>