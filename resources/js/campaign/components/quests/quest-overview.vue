<template>
    <div id="quests">
        <h1>Quests</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link v-if="$store.getters.can('create', 'quest')"
                             class="uk-button uk-button-primary" :to="{name: 'quest-create'}">
                    <i class="fas fa-plus"></i> Add quest
                </router-link>
                <paginated-table v-if="quests != null && quests.data.length > 0"
                                 :actions="actions"
                                 :columns="columns"
                                 module="Quests"
                                 :records="quests"
                                 @edit="$router.push({name: 'quest-edit', params: {id: $event.id}})"
                                 @view="$router.push({name: 'quest', params: {id: $event.id}})"
                                 @destroy="destroy"/>
                <p v-else class="uk-text-center">
                    <i v-if="quests == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>Your quest log is empty!</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import UIKit from 'uikit';
    import PaginatedTable from "@/components/partial/paginated-table";

    export default {
        name: "QuestOverview",
        created() {
            this.$store.dispatch('Quests/load');
        },
        data() {
            return {
                actions: [
                    {name: 'destroy', icon: 'trash', classes: 'uk-text-danger'},
                    {name: 'edit', icon: 'edit'},
                    {name: 'view', icon: 'eye'}
                ],
                columns: [
                    {title: 'Title', name: 'title'},
                    {
                        title: 'Completion',
                        name: 'objectives',
                        format(objectives) {
                            return `${objectives.filter((item) => item.status == 1).length}/${objectives.length}`
                        }
                    },
                    {
                        title: 'Location',
                        name: 'location',
                        format(location) {
                            return location || 'Not specified';
                        }
                    },
                ]
            }
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
        },
        components: {PaginatedTable}
    }
</script>
