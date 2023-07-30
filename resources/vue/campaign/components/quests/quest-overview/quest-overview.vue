<template>
    <div id="quests">
        <h1>Quests</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link v-if="can('create', 'quest')"
                             class="uk-button uk-button-primary" :to="{name: 'quest-create'}">
                    <i class="fas fa-plus"></i> Add quest
                </router-link>
                <paginated-table v-if="quests != null && quests.data.length > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :store="store"
                                 :records="quests"
                                 @destroy="state.destroy"/>
                <p v-else class="uk-text-center">
                    <i v-if="quests == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>Your quest log is empty!</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useMainStore } from '@campaign/stores/main'
import { useQuestStore } from '@campaign/stores/quests'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useQuestOverviewState } from './quest-overview.state'
import { ui } from './quest-overview.ui'

export default {
    name: 'QuestOverview',
    setup() {
        const store = useQuestStore()
        const { quests } = storeToRefs(store)
        const main = useMainStore()
        const state = useQuestOverviewState(store)

        onMounted(() => store.load())

        return { can: main.can, quests, state, store, ui }
    },
    components: { PaginatedTable }
}
</script>
