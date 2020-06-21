<template>
    <div>
        <h1>{{ quest ? quest.title : '' }}</h1>
        <div class="uk-section uk-section-default">
            <div id="quest" v-if="quest" class="uk-container padded">
                <div uk-grid>
                    <div class="uk-width-1-2">
                        <h2>Description</h2>
                        <div class="uk-margin" v-html="quest.description">{{ 'No description' }}</div>
                    </div>
                    <div class="uk-width-1-2">
                        <h2>Objectives</h2>
                        <div class="uk-card uk-card-body objective" v-for="objective in objectives">
                            <span class="actions uk-float-right">
                                <i class="fas fa-check-circle fa-fw" :class="{'uk-text-success': objective.status == 1}" @click.prevent="complete(objective)"></i>
                                <i class="fas fa-times-circle fa-fw" :class="{'uk-text-danger': objective.status == 2}" @click.prevent="fail(objective)"></i>
                            </span>
                            <span :class="{'uk-text-success': objective.status == 1, 'uk-text-danger': objective.status == 2}">
                                <i v-if="objective.status == 1" class="fas fa-check-circle fa-fw"></i>
                                <i v-if="objective.status == 2" class="fas fa-times-circle fa-fw"></i>
                                <s v-if="objective.status > 0">{{ objective.name }}</s>
                                <span v-else>{{ objective.name }}</span>
                            </span>
                        </div>
                        <h2 v-if="optional.length > 0">Optional</h2>
                        <div v-if="optional.length > 0" class="uk-card uk-card-body objective" v-for="objective in optional">
                            <span class="actions uk-float-right">
                                <i class="fas fa-check-circle fa-fw" :class="{'uk-text-success': objective.status == 1}" @click.prevent="complete(objective)"></i>
                                <i class="fas fa-times-circle fa-fw" :class="{'uk-text-danger': objective.status == 2}" @click.prevent="fail(objective)"></i>
                            </span>
                            <span :class="{'uk-text-success': objective.status == 1, 'uk-text-danger': objective.status == 2}">
                                <i v-if="objective.status == 1" class="fas fa-check-circle fa-fw"></i>
                                <i v-if="objective.status == 2" class="fas fa-times-circle fa-fw"></i>
                                <s v-if="objective.status > 0">{{ objective.name }}</s>
                                <span v-else>{{ objective.name }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <br><br>
                <p class="uk-margin">
                    <router-link class="uk-button uk-button-text" :to="{name: 'quests'}">
                        <i class="fa fa-chevron-left fa-fw"></i> Back to quests
                    </router-link>
                </p>
            </div>
            <p v-else class="uk-text-center">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "quest",
        props: ['id'],
        created() {
            this.$store.dispatch('Quests/find', this.id)
                .then((quest) => {
                    this.$set(this, 'quest', quest);
                });
        },
        data() {
            return {
                quest: null
            }
        },
        methods: {
            complete(objective) {
                let url = `/campaign/quests/${this.id}/objectives/${objective.id}/toggle`;
                axios.post(url, {status: objective.status == 1 ? 0 : 1})
                    .then((response) => {
                        objective.status = response.data.status;
                    });
            },
            fail(objective) {
                let url = `/campaign/quests/${this.id}/objectives/${objective.id}/toggle`;
                axios.post(url, {status: objective.status < 2 ? 2 : 0})
                    .then((response) => {
                        objective.status = response.data.status;
                    });
            }
        },
        computed: {
            objectives() {
                return this.quest.objectives.filter((item) => {
                    return !item.optional;
                }) || [];
            },
            optional() {
                return this.quest.objectives.filter((item) => {
                    return item.optional;
                }) || [];
            }
        }
    }
</script>