<template>
    <div id="spells">
        <h1>Spells</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'spell-create'}">
                    <i class="fas fa-plus"></i> Add spell
                </router-link>
                <paginated-table v-if="state.spells != null"
                                 :actions="ui.actions"
                                 :columns="ui.columns"
                                 module="Spells"
                                 :records="state.spells"
                                 @view="router.push({name: 'spell', params: {id: $event.id}})"
                                 @destroy="state.destroy" searchable/>
                <p v-else class="uk-text-center">
                    <i v-if="state.spells == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No spells found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import PaginatedTable from "@components/partial/paginated-table";
import { useStore } from "vuex";
import { onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
    name: "spell-overview",
    components: { PaginatedTable },
    setup() {
        const store = useStore()
        const router = useRouter()

        onMounted(() => store.dispatch('Spells/load'))

        return { router, state, ui }
    }
}
</script>