<template>
    <div id="locations">
        <h1>Quests</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'quest-create'}">
                    <i class="fas fa-plus"></i> Add quest
                </router-link>
                <table class="uk-table uk-table-divider" v-if="quests != null && quests.data.length > 0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Completion</th>
                        <th>Location</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="quest in quests.data">
                        <td class="uk-width-small">
                            <ul class="uk-iconnav">
                                <li>
                                    <a href="/" class="uk-text-danger" @click.prevent="destroy(quest)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'quest-edit', params: {id: quest.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'quest', params: {id: quest.id}}">
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ quest.title }}</td>
                        <td>
                            {{ `${quest.objectives.filter((item) => {item.completed == 1}).length}/${quest.objectives.length}` }}
                        </td>
                        <td>{{ quest.location || 'Not specified' }}</td>
                    </tr>
                    </tbody>
                </table>
                <p v-else class="uk-text-center">
                    <i v-if="quests == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        Your quest log is empty!
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import UIKit from 'uikit';

    export default {
        name: "QuestOverview",
        created() {
            this.$store.dispatch('Quests/load');
        },
        methods: {
            destroy(quest) {
                UIKit.modal.confirm('Are you sure you want to delete this quest?', {
                    labels: {
                        ok: 'Delete',
                        cancel: 'cancel'
                    }
                })
                    .then(() => {
                        this.$store.dispatch('Quests/destroy', quest)
                            .then(() => {
                                this.$store.dispatch('Quests/load');
                            })
                    });
            }
        },
        computed: {
            ...mapState('Quests', ['quests'])
        }
    }
</script>