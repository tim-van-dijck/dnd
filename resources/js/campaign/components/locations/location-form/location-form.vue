<template>
    <div>
        <h1>{{ ui.title.value }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.input" id="location-form" class="uk-form-stacked">
                    <ul v-if="can('edit', 'role')" uk-tab>
                        <li :class="{'uk-active': ui.tab === 'details'}">
                            <a href="" @click.prevent="ui.setTab('details')">Details</a>
                        </li>
                        <li :class="{'uk-active': ui.tab === 'permissions'}">
                            <a href="" @click.prevent="ui.setTab('permissions')">Permissions</a>
                        </li>
                    </ul>
                    <div v-show="ui.tab === 'details'" uk-grid>
                        <div class="uk-width-1-2">
                            <div class="uk-margin">
                                <label for="name" class="uk-form-label"
                                       :class="{'uk-text-danger': state.errors.hasOwnProperty('name')}">Name*</label>
                                <input id="name" title="name" type="text" class="uk-input"
                                       :class="{'uk-form-danger': state.errors.hasOwnProperty('name')}"
                                       v-model="state.input.name">
                            </div>
                            <div class="uk-margin">
                                <label for="type" class="uk-form-label"
                                       :class="{'uk-text-danger': state.errors.hasOwnProperty('type')}">Type*</label>
                                <input id="type" name="type" type="text" class="uk-input"
                                       :class="{'uk-form-danger': state.errors.hasOwnProperty('type')}"
                                       v-model="state.input.type">
                            </div>
                            <!-- <div class="uk-margin">
                                <label for="location" class="uk-form-label"
                                       :class="{'uk-text-danger': state.errors.hasOwnProperty('location_id')}">Location</label>
                                <v-select id="location" name="location_id" class="uk-select"
                                          :class="{'uk-form-danger': state.errors.hasOwnProperty('location_id')}"
                                          @search="onSearch" :options="locations"
                                          :reduce="item => item.value"
                                          v-model="state.location.location_id">
                                </v-select>
                            </div>-->
                            <div class="uk-margin">
                                <label for="map" class="uk-form-label"
                                       :class="{'uk-text-danger': state.errors.hasOwnProperty('map')}">Map</label>
                                <img class="preview-image" v-if="state.src" :src="state.src" alt="Uploaded map image"
                                     width="300"
                                     height="300">
                                <input id="map" name="map" type="file"
                                       :class="{'uk-form-danger': state.errors.hasOwnProperty('map')}"
                                       @change="state.handleFileChange">
                            </div>
                            <hr>
                            <div class="uk-margin uk-form-controls">
                                <label for="private"
                                       :class="{'uk-text-danger': state.errors.hasOwnProperty('private')}">
                                    <input id="private" name="private" type="checkbox" class="uk-checkbox"
                                           :class="{'uk-form-danger': state.errors.hasOwnProperty('private')}"
                                           v-model="state.input.private">
                                    Private
                                </label>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <label for="description" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description" v-model="state.input.description"
                                         height="600"/>
                        </div>
                    </div>
                    <permissions-form v-show="ui.tab === 'permissions' && can('edit', 'role')"
                                      entity="location" :id="id"
                                      v-model="state.input.permissions"/>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary uk-margin-right"
                                @click.prevent="state.handleSubmit">Save
                        </button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'locations'}">
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
import { useLocationStore } from '@campaign/stores/locations'
import { useMainStore } from '@campaign/stores/main'
import HtmlEditor from '@components/partial/html-editor'
import Editor from '@tinymce/tinymce-vue'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, onMounted, reactive } from 'vue'
import PermissionsForm from '../../partial/permissions-form'
import { useLocationFormState } from './location-form.state'

export default {
    name: 'LocationForm',
    props: ['id'],
    setup(props) {
        const store = useLocationStore()
        const main = useMainStore()
        const state = useLocationFormState(store, main.can, props.id)
        const locations = storeToRefs(store)

        onMounted(() => {
            store.load()
            state.init()
        })
        return {
            can: main.can,
            state,
            store,
            locations,
            ui: {
                tab: reactive('details'),
                title: computed(() => props.id ? `Edit ${state.input?.name || 'location'}` : 'Add location')
            }
        }
    },
    components: {
        PermissionsForm,
        HtmlEditor,
        Editor
    }
}
</script>