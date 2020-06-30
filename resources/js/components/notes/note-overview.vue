<template>
    <div id="Notes">
        <h1>Notes</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'note-create'}">
                    <i class="fas fa-plus"></i> Add note
                </router-link>
                <paginated-table v-if="notes != null && notes.data.length > 0"
                                 :actions="actions"
                                 :columns="columns"
                                 module="Notes"
                                 :records="notes"
                                 @edit="$router.push({name: 'note-edit', params: {id: $event.id}})"
                                 @view="$router.push({name: 'note', params: {id: $event.id}})"
                                 @destroy="destroy" />
                <p v-else class="uk-text-center">
                    <i v-if="notes == null" class="fas fa-sync fa-spin fa-2x"></i>
                    <span v-else>
                        No notes found
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import * as UIKit from "uikit";
    import PaginatedTable from "../partial/paginated-table";

    export default {
        name: "NoteOverview",
        components: {PaginatedTable},
        created() {
            this.$store.dispatch('Notes/load');
        },
        data() {
            return {
                actions: [
                    {name: 'destroy', icon: 'trash', classes: 'uk-text-danger'},
                    {name: 'edit', icon: 'edit'},
                    {name: 'view', icon: 'eye'}
                ],
                columns: [
                    {
                        title: 'Name',
                        name: 'name',
                    }
                ]
            }
        },
        methods: {
            destroy(note) {
                UIKit.modal.confirm('Are you sure you want to delete this note?', {
                    labels: {
                        ok: 'Delete',
                        cancel: 'cancel'
                    }
                })
                    .then(() => {
                        this.$store.dispatch('Notes/destroy', note)
                            .then(() => {
                                this.$store.dispatch('Notes/load');
                            })
                    });
            }
        },
        computed: {
            ...mapState('Notes', ['notes'])
        }
    }
</script>