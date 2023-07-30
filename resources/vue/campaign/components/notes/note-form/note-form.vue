<template>
    <div>
        <h1>{{ ui.title.value }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.input" action="">
                    <ul v-if="can('edit', 'role')" uk-tab>
                        <li :class="{'uk-active': ui.tab === 'details'}">
                            <a href="" @click.prevent="ui.setTab('details')">Details</a>
                        </li>
                        <li :class="{'uk-active': ui.tab === 'permissions'}">
                            <a href="" @click.prevent="ui.setTab('permissions')">Permissions</a>
                        </li>
                    </ul>
                    <div v-show="ui.tab === 'details'">
                        <div class="uk-margin">
                            <label for="name" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('name')}">Name*</label>
                            <input id="name" name="name" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('name')}"
                                   v-model="state.input.name">
                        </div>
                        <div class="uk-margin">
                            <label for="content" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('content')}">Content*</label>
                            <html-editor id="note-content" name="note-content" height="800"
                                         v-model="state.input.content"></html-editor>
                        </div>
                        <div class="uk-margin">
                            <label for="private" class="uk-form-label">
                                <input id="private" name="private" type="checkbox" class="uk-checkbox"
                                       v-model="state.input.private">
                                Private
                            </label>
                        </div>
                    </div>
                    <permissions-form v-show="ui.tab === 'permissions' && can('edit', 'role')"
                                      entity="note" :id="id"
                                      v-model="state.input.permissions"/>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="state.save">
                            Save
                        </button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'notes'}">
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
import { useMainStore } from '@campaign/stores/main'
import { useNoteStore } from '@campaign/stores/notes'
import HtmlEditor from '@components/partial/html-editor'
import { computed, onMounted, reactive } from 'vue'
import PermissionsForm from '../../partial/permissions-form'
import { useNoteFormState } from './note-form.state'

export default {
    name: 'NoteForm',
    props: ['id'],
    components: { PermissionsForm, HtmlEditor },
    setup(props) {
        const store = useNoteStore()
        const main = useMainStore()
        const state = useNoteFormState(store, main.can)

        onMounted(() => state.init(props.id))

        return {
            can: main.can,
            state,
            ui: reactive({
                tab: 'details',
                setTab(tab) {
                    this.tab = tab
                },
                title: computed(() => props.id ? `Edit ${state.input.title || 'note'}` : 'Create new note')
            })
        }
    }
}
</script>