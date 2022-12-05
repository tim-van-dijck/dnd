<template>
    <div id="Notes">
        <h1>Notes</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'note-create'}">
                    <i class="fas fa-plus"></i> Add note
                </router-link>
                <paginated-table v-if="notes != null && notes.data.length > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :store="store"
                                 :records="notes"
                                 @destroy="state.destroy"/>
                <p v-else class="uk-text-center">
                    <i v-if="notes == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No notes found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useMainStore } from '@campaign/stores/main'
import { useNoteStore } from '@campaign/stores/notes'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useNoteOverviewState } from './note-overview.state'
import { ui } from './note-overview.ui'

export default {
    name: 'NoteOverview',
    components: { PaginatedTable },
    setup() {
        const store = useNoteStore()
        const main = useMainStore()
        const { notes } = storeToRefs(store)
        const state = useNoteOverviewState(store)

        onMounted(() => store.load())

        return { can: main.can, notes, state, store, ui }
    }
}
</script>