<template>
    <div class="player-characters">
        <router-link class="uk-button uk-button-primary" :to="{name: 'pc-create', params: {type: 'player'}}">
            <i class="fas fa-plus"></i> Add character
        </router-link>
        <paginated-table v-if="characters != null && characters.data.length > 0"
                         :actions="ui.actions"
                         :columns="ui.columns"
                         :store="store"
                         :records="characters"
                         @destroy="state.destroy"/>
        <p v-else class="uk-text-center">
            <i v-if="characters == null" class="fas fa-sync fa-spin fa-2x"></i>
            <span v-else>
                No characters found
            </span>
        </p>
    </div>
</template>

<script>
import { useCharacterStore } from '@campaign/stores/characters'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useCharacterOverviewState } from './player-character-overview.state'
import { ui } from './player-character-overview.ui'

export default {
    name: 'PlayerCharacterOverview',
    components: { PaginatedTable },
    setup() {
        const store = useCharacterStore()
        const { characters } = storeToRefs(store)
        const state = useCharacterOverviewState(store)

        onMounted(() => {
            store.load('player')
            store.loadRaces()
        })

        return { characters, state, store, ui }
    }
}
</script>