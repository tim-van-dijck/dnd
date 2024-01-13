<template>
    <div id="races">
        <h1>Races</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'race-create'}">
                    <i class="fas fa-plus"></i> Add race
                </router-link>
                <paginated-table v-if="(races?.data?.length || 0) > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :records="races"
                                 :store="store"
                                 @destroy="state.destroy"
                                 searchable/>
            </div>
        </div>
    </div>
</template>

<script>
import { useRaceStore } from '@admin/stores/races'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useState } from './race-overview.state'
import { ui } from './race-overview.ui'

export default {
    name: 'race-overview',
    components: { PaginatedTable },
    setup() {
        const store = useRaceStore()
        const { races } = storeToRefs(store)
        const { state } = useState(store)

        onMounted(() => store.load())

        return {
            races,
            state,
            store,
            ui
        }
    }
}
</script>