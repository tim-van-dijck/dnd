<template>
    <div id="spells">
        <h1>Spells</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'spell-create'}">
                    <i class="fas fa-plus"></i> Add spell
                </router-link>
                <paginated-table v-if="(spells?.data?.length || 0) > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :records="spells"
                                 :store="store"
                                 @view="router.push({name: 'spell', params: {id: $event.id}})"
                                 @destroy="state.destroy"
                                 searchable
                />
                <p v-else class="uk-text-center">
                    <i v-if="spells == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No spells found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useSpellStore } from '@admin/stores/spells'
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useState } from './spell-overview.state'
import { ui } from './spell-overview.ui'

export default {
    name: 'spell-overview',
    components: { PaginatedTable },
    setup() {
        const store = useSpellStore()
        const router = useRouter()
        const { state } = useState(store)
        const { spells } = storeToRefs(store)

        onMounted(() => store.load())

        return { router, spells, state, store, ui }
    }
}
</script>