<template>
    <div id="campaigns">
        <h1>Campaigns</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <paginated-table v-if="(campaigns?.data?.length || 0) > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 :store="store"
                                 :records="campaigns"
                                 @destroy="state.destroy"/>
                <p v-else class="uk-text-center">
                    <i v-if="state.campaigns == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No campaigns found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import PaginatedTable from '@components/partial/paginated-table'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useCampaignStore } from '../../../stores/campaigns'
import { useCampaignOverviewState } from './campaign-overview.state'
import { ui } from './campaign-overview.ui'

export default {
    name: 'campaign-overview',
    components: { PaginatedTable },
    setup() {
        const store = useCampaignStore()
        const state = useCampaignOverviewState(store)
        const { campaigns } = storeToRefs(store)

        onMounted(() => store.load())

        return { campaigns, state, store, ui }
    }
}
</script>