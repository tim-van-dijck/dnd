<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="quest" action="">
                    <div uk-grid>
                        <div class="uk-width-1-2">
                            <h2>Details</h2>
                            <div class="uk-margin">
                                <label for="title" class="uk-form-label">Title</label>
                                <input id="title" name="title" type="text" class="uk-input" v-model="quest.title">
                            </div>
                            <div class="uk-margin">
                                <label for="location" class="uk-form-label">Location</label>
                                <v-select id="location" name="location"
                                          @search="onSearch" :options="locations"
                                          v-model="quest.location_id">
                                </v-select>
                            </div>
                            <div class="uk-margin">
                                <label for="description" class="uk-form-label">Description</label>
                                <html-editor id="description" name="description" v-model="quest.description"></html-editor>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <h2>Objectives</h2>
                            <div class="uk-card uk-card-body objective" v-for="(objective, index) in quest.objectives">
                                <div class="uk-margin">
                                    <input type="text" class="uk-input" v-model="objective.name">
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
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import HtmlEditor from "../partial/html-editor";
    import VSelect from 'vue-select';
    import _ from 'lodash';

    export default {
        name: "QuestForm",
        props: ['id'],
        created() {
            if (this.id) {
                this.$store.dispatch('Quests/find', this.id)
                    .then((quest) => {
                        this.quest = JSON.parse(JSON.stringify(quest));
                    });
            } else {
                this.quest = {
                    objectives: [],
                };
                this.addObjective();
            }
        },
        data() {
            return {
                quest: null,
                locations: []
            }
        },
        methods: {
            addObjective() {
                this.quest.objectives.push({name: '', optional: false});
            },
            removeObjective(index) {
                this.quest.objectives.splice(index, 1);
            },
            save() {
                let promise;
                let quest = {
                    title: this.quest.title,
                    description: this.quest.description,
                    objectives: []
                };
                if (this.quest.location_id > 0) {
                    quest.location_id = this.quest.location_id;
                }

                for (let objective of this.quest.objectives) {
                    if (objective.title != '') {
                        quest.objectives.push(objective);
                    }
                }
                if (this.id) {
                    promise = this.$store.dispatch('Quests/update', {quest, id: this.id})
                } else {
                    promise = this.$store.dispatch('Quests/store', quest)
                }
                promise.then(() => {
                    if (Object.keys(this.errors).length === 0) {
                        this.$store.dispatch('Quests/load');
                        this.$router.push({name: 'quests'});
                    }
                });
            },
            onSearch(query, loading) {
                if (query.length > 2) {
                    loading(true);
                    this.search(query, loading, this);
                }
            },
            search: _.debounce((query, loading, vm) => {
                axios.get(`/campaign/locations?filter[query]=${escape(query)}&page[number]=1&page[size]=10`)
                    .then((response) => {
                        let locations = response.data.data.map((item) => {
                            return {value: item.id, label: item.name};
                        });
                        vm.$set(vm, 'locations', locations);
                        loading(false);
                    });
            }, 1000)
        },
        computed: {
            ...mapState('Quests', ['errors']),
            title() {
                if (this.id) {
                    return `Edit ${this.quest && this.quest.title.length > 0 ? this.quest.title : 'quest'}`;
                } else {
                    return 'Create new quest';
                }
            }
        },
        components: {HtmlEditor, VSelect}
    }
</script>