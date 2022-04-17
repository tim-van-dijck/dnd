<template>
    <h1>{{ title }}</h1>
    <div class="uk-section uk-section-default">
        <div class="uk-container padded">
            <form v-if="quest" action="">
                <ul v-if="$store.getters.can('edit', 'role')" uk-tab>
                    <li :class="{'uk-active': tab === 'details'}">
                        <a href="" @click.prevent="tab = 'details'">Details</a>
                    </li>
                    <li :class="{'uk-active': tab === 'permissions'}">
                        <a href="" @click.prevent="tab = 'permissions'">Permissions</a>
                    </li>
                </ul>
                <div v-show="tab === 'details'" uk-grid>
                    <div class="uk-width-1-2">
                        <h2>Details</h2>
                        <div class="uk-margin">
                            <label for="title" class="uk-form-label"
                                   :class="{'uk-text-danger': errors.hasOwnProperty('title')}">Title</label>
                            <input id="title" name="title" type="text" class="uk-input"
                                   :class="{'uk-form-danger': errors.hasOwnProperty('title')}" v-model="quest.title">
                        </div>
                        <!--                        <div class="uk-margin">-->
                        <!--                            <label for="location" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('location_id')}">Location</label>-->
                        <!--                            <v-select id="location" name="location"-->
                        <!--                                      :class="{'uk-form-danger': errors.hasOwnProperty('location_id')}"-->
                        <!--                                      @search="onSearch" :options="locations" :reduce="item => item.value"-->
                        <!--                                      v-model="quest.location_id">-->
                        <!--                            </v-select>-->
                        <!--                        </div>-->
                        <div class="uk-margin">
                            <label for="description" class="uk-form-label"
                                   :class="{'uk-text-danger': errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description"
                                         :class="{'uk-form-danger': errors.hasOwnProperty('description')}"
                                         v-model="quest.description"></html-editor>
                        </div>
                        <div class="uk-margin">
                            <label for="private" class="uk-form-label"
                                   :class="{'uk-text-danger': errors.hasOwnProperty('private')}">
                                <input id="private" name="private" type="checkbox" class="uk-checkbox"
                                       :class="{'uk-form-danger': errors.hasOwnProperty('private')}"
                                       v-model="quest.private">
                                Private
                            </label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h2 :class="{'uk-text-danger': Object.keys(errors).filter(field => field.includes('objectives')).length > 0}">
                            Objectives</h2>
                        <div class="uk-card uk-card-body uk-card-secondary objective"
                             v-for="(objective, index) in quest.objectives">
                            <div class="uk-margin">
                                <input type="text" class="uk-input"
                                       :class="{'uk-form-danger': errors.hasOwnProperty(`objectives.${index}.name`)}"
                                       v-model="objective.name">
                            </div>
                            <div class="uk-margin">
                                <label :for="`optional_${index}`">
                                    <input :id="`optional_${index}`" type="checkbox" class="uk-checkbox"
                                           v-model="objective.optional">
                                    Optional
                                </label>
                                <a v-if="quest.objectives.length > 1"
                                   class="uk-text-danger uk-float-right" @click.prevent="removeObjective(index)">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <button class="uk-align-center uk-button uk-button-primary uk-button-round"
                                @click.prevent="addObjective">
                            <i class="fas fa-plus fa-fw"></i>
                        </button>
                    </div>
                </div>
                <permissions-form v-show="tab === 'permissions' && $store.getters.can('edit', 'role')"
                                  entity="quest" :id="id"
                                  v-model="quest.permissions"/>
                <p class="uk-margin">
                    <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
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
import { useStore } from "vuex";
import HtmlEditor from "../../../../components/partial/html-editor";
import PermissionsForm from "../../partial/permissions-form";
import { computed, onMounted, reactive } from "vue";
import { search } from "./quest-form.methods";

export default {
    name: "QuestForm",
    props: ['id'],
    setup(props) {
        const store = useStore();
        let input;
        onMounted(() => {
            if (props.id) {
                store.dispatch('Quests/find', props.id)
                    .then((quest) => {
                        input = reactive({ ...quest });
                        if (!quest.hasOwnProperty('permissions')) {
                            input.permissions = {};
                        }
                    });
            } else {
                input = reactive({
                    objectives: [],
                    permissions: {}
                });
                addObjective();
            }
        })
        return {
            locations: reactive([]),
            tab: reactive('details'),
            quest: input,
            errors: reactive({}),
            addObjective: () => {
                input.objectives.push({ name: '', optional: false })
            },
            onSearch: (query, loading) => {
                if (query.length > 2) {
                    loading(true);
                    search(query, loading);
                }
            },
            title: computed(() => props.id ? `Edit ${input.title || 'quest'}` : 'Create new quest')
        }
    },
    components: { PermissionsForm, HtmlEditor }
}
</script>