<template>
    <div id="campaigns">
        <h1>Campaigns</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <paginated-table v-if="(state.campaigns?.data?.length || 0) > 0"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 module="Campaigns"
                                 :records="state.campaigns"
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
import PaginatedTable from "@components/partial/paginated-table";
import { state } from "./campaign-overview.state";
import { ui } from "./campaign-overview.ui";
import { onMounted } from "vue";
import { useStore } from "vuex";

export default {
    name: "campaign-overview",
    components: { PaginatedTable },
    setup() {
        const store = useStore();
        onMounted(() => store.dispatch('Campaigns/load'))
        return { state, ui }
    }
}
</script>