<template>
    <div id="journal">
        <h1>Journal</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'journal-create'}">
                    <i class="fas fa-plus"></i> Add journal entry
                </router-link>
                <div class="uk-width uk-margin" v-if="entries !== null && entries.length > 0">
                    <draggable v-model="entries" handle=".sort-handle" @sort="store.sort($event)" item-key="id">
                        <template #item="{entry}">
                            <div class="uk-width uk-card uk-card-body uk-card-secondary">
                                <div class="uk-flex">
                                    <div class="uk-width">
                                        <h3 class="uk-card-title">
                                            <router-link class="uk-link-heading uk-width uk-display-block"
                                                         :to="{name: 'journal-player-character', params: {id: entry.id}}">
                                                {{ entry.title }}
                                            </router-link>
                                        </h3>
                                    </div>
                                    <div class="uk-flex uk-flex-between">
                                        <router-link tag="button" class="uk-button uk-button-round uk-button-default"
                                                     :to="{name: 'journal-player-character', params: {id: entry.id}}">
                                            <i class="fas fa-eye"></i>
                                        </router-link>
                                        <router-link tag="button" class="uk-button uk-button-round uk-button-default"
                                                     :to="{name: 'journal-edit', params: {id: entry.id}}">
                                            <i class="fas fa-edit"></i>
                                        </router-link>
                                        <button class="uk-text-danger uk-button uk-button-default uk-button-round"
                                                @click.prevent="state.destroy(entry)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button"
                                                class="sort-handle uk-button uk-button-round uk-button-default">
                                            <i class="fas fa-grip-vertical"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
                <p v-else class="uk-text-center">
                    <i v-if="entries == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No journal entries found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useJournalStore } from '@campaign/stores/journal'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import draggable from 'vuedraggable'
import { useState } from './journal-overview.state'

export default {
    name: 'journal-overview',
    components: { draggable },
    setup() {
        const store = useJournalStore()
        const { entries } = storeToRefs(store)
        const state = useState(store)

        onMounted(() => store.load())

        return { entries, state, store }
    }
}
</script>