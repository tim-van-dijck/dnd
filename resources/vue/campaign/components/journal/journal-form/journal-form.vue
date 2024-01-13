<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.entry" action="">
                    <div class="uk-margin">
                        <label for="title" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('title')}">Title*</label>
                        <input id="title" name="title" type="text" class="uk-input"
                               :class="{'uk-form-danger': state.errors.hasOwnProperty('title')}"
                               v-model="state.entry.title">
                    </div>
                    <div class="uk-margin">
                        <label for="entry-content" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('content')}">Content*</label>
                        <html-editor id="entry-content" name="entry-content" height="800"
                                     v-model="state.entry.content"></html-editor>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="state.save">Save
                        </button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'journal'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useJournalStore } from '@campaign/stores/journal'
import HtmlEditor from '@components/partial/html-editor'
import { computed, onMounted } from 'vue'
import { useJournalFormState } from './journal-form.state'

export default {
    name: 'journal-form',
    props: ['id'],
    components: { HtmlEditor },
    setup(props) {
        const store = useJournalStore()
        const state = useJournalFormState(store)
        onMounted(() => {
            if (props.id) {
                store.find(props.id).then((entry) => state.setEntry(entry))
            } else {
                state.setEntry({})
            }
        })

        const title = computed(() => {
            if (props.id) {
                return `Edit ${state.entry && state.entry.title.length > 0 ? state.entry.title : 'entry'}`
            } else {
                return 'Create new entry'
            }
        })

        return { state, title }
    }
}
</script>