<template>
    <div id="races">
        <h1>Races</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'race-create'}">
                    <i class="fas fa-plus"></i> Add race
                </router-link>
                <paginated-table :actions="ui.actions"
                                 :columns="ui.columns"
                                 module="Races"
                                 :records="state.races"
                                 @destroy="state.destroy"
                                 searchable/>
            </div>
        </div>
    </div>
</template>

<script>
import PaginatedTable from "@components/partial/paginated-table";
import { ui } from "./race-overview.ui";
import { onMounted } from "vue";
import { useStore } from "vuex";

export default {
    name: "race-overview",
    components: { PaginatedTable },
    setup() {
        const store = useStore()
        onMounted(() => store.dispatch('Races/load'))
        return {
            state,
            ui,
        }
    }
}
</script>