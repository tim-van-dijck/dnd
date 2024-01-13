<template>
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
                <div v-show="ui.tab === 'details'" uk-grid>
                    <div class="uk-width-1-2">
                        <h2>Details</h2>
                        <div class="uk-margin">
                            <label for="title" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('title')}">Title</label>
                            <input id="title" name="title" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('title')}"
                                   v-model="state.input.title">
                        </div>
                        <!--                        <div class="uk-margin">
                                                    <label for="location" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('location_id')}">Location</label>
                                                    <v-select id="location" name="location"
                                                              :class="{'uk-form-danger': errors.hasOwnProperty('location_id')}"
                                                              @search="onSearch" :options="locations" :reduce="item => item.value"
                                                              v-model="quest.location_id">
                                                    </v-select>
                                                </div>-->
                        <div class="uk-margin">
                            <label for="description" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description"
                                         :class="{'uk-form-danger': state.errors.hasOwnProperty('description')}"
                                         v-model="state.input.description"></html-editor>
                        </div>
                        <div class="uk-margin">
                            <label for="private" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('private')}">
                                <input id="private" name="private" type="checkbox" class="uk-checkbox"
                                       :class="{'uk-form-danger': state.errors.hasOwnProperty('private')}"
                                       v-model="state.input.private">
                                Private
                            </label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h2 :class="{'uk-text-danger': Object.keys(state.errors).filter(field => field.includes('objectives')).length > 0}">
                            Objectives
                        </h2>
                        <div class="uk-card uk-card-body uk-card-secondary objective"
                             v-for="(objective, index) in state.input.objectives">
                            <div class="uk-margin">
                                <input type="text" class="uk-input"
                                       :class="{'uk-form-danger': state.errors.hasOwnProperty(`objectives.${index}.name`)}"
                                       v-model="objective.name">
                            </div>
                            <div class="uk-margin">
                                <label :for="`optional_${index}`">
                                    <input :id="`optional_${index}`" type="checkbox" class="uk-checkbox"
                                           v-model="objective.optional">
                                    Optional
                                </label>
                                <a v-if="state.input.objectives.length > 1"
                                   class="uk-text-danger uk-float-right" @click.prevent="state.removeObjective(index)">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <button class="uk-align-center uk-button uk-button-primary uk-button-round"
                                @click.prevent="state.addObjective">
                            <i class="fas fa-plus fa-fw"></i>
                        </button>
                    </div>
                </div>
                <permissions-form v-show="ui.tab === 'permissions' && can('edit', 'role')"
                                  entity="quest" :id="id"
                                  v-model="state.input.permissions"/>
                <p class="uk-margin">
                    <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="state.save">
                        Save
                    </button>
                    <router-link class="uk-button uk-button-danger" :to="{name: 'quests'}">
                        Cancel
                    </router-link>
                </p>
            </form>
            <p v-else class="uk-text-center">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </p>
        </div>
    </div>
</template>

<script>
import { useMainStore } from '@campaign/stores/main'
import { useQuestStore } from '@campaign/stores/quests'
import HtmlEditor from '@components/partial/html-editor'
import { computed, onMounted, reactive } from 'vue'
import PermissionsForm from '../../partial/permissions-form'
import { useQuestFormState, useSearch } from './quest-form.state'

export default {
    name: 'QuestForm',
    props: ['id'],
    setup(props) {
        const store = useQuestStore()
        const main = useMainStore()
        const { state } = useQuestFormState(store, main.can)
        const { locations, search } = useSearch()

        onMounted(() => state.init(props.id))

        return {
            id: props.id,
            can: main.can,
            locations,
            state,
            onSearch: (query, loading) => {
                if (query.length > 2) {
                    loading(true)
                    search?.(query, loading)
                }
            },
            ui: reactive({
                tab: 'details',
                setTab(tab) {
                    this.tab = tab
                },
                title: computed(() => props.id ? `Edit ${state.input.title || 'quest'}` : 'Create new quest')
            })
        }
    },
    components: { PermissionsForm, HtmlEditor }
}
</script>