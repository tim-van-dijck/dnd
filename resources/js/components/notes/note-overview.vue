<template>
    <div id="Notes">
        <h1>Notes</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <router-link class="uk-button uk-button-primary" :to="{name: 'note-create'}">
                    <i class="fas fa-plus"></i> Add note
                </router-link>
                <table class="uk-table uk-table-divider" v-if="notes != null && notes.data.length > 0">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="note in notes.data">
                        <td class="uk-width-small">
                            <ul class="uk-iconnav">
                                <li>
                                    <a href="/" class="uk-text-danger" @click.prevent="destroy(note)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </li>
                                <li>
                                    <router-link :to="{name: 'note-edit', params: {id: note.id}}">
                                        <i class="fas fa-edit"></i>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name: 'note', params: {id: note.id}}">
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </td>
                        <td>{{ note.name }}</td>
                    </tr>
                    </tbody>
                </table>
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

    export default {
        name: "NoteOverview",
        created() {
            this.$store.dispatch('Notes/load');
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