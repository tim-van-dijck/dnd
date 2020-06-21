<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="note" action="">
                    <div class="uk-margin">
                        <label for="name" class="uk-form-label">Name</label>
                        <input id="name" name="name" type="text" class="uk-input" v-model="note.name">
                    </div>
                    <div class="uk-margin">
                        <label for="content" class="uk-form-label">Content</label>
                        <html-editor id="content" name="content" height="800" v-model="note.content"></html-editor>
                    </div>
                    <div class="uk-margin">
                        <label for="private" class="uk-form-label">
                            <input id="private" name="private" type="checkbox" class="uk-checkbox" v-model="note.private">
                            Private
                        </label>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
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
    import {mapState} from "vuex";
    import HtmlEditor from "../partial/html-editor";

    export default {
        name: "NoteForm",
        props: ['id'],
        components: {HtmlEditor},
        created() {
            if (this.id) {
                this.$store.dispatch('Notes/find', this.id)
                    .then((note) => {
                        this.note = JSON.parse(JSON.stringify(note));
                    });
            } else {
                this.note = {};
            }
        },
        data() {
            return {
                note: null
            }
        },
        methods: {
            save() {
                let promise;
                if (this.id) {
                    promise = this.$store.dispatch('Notes/update', {id: this.id, note: this.note});
                } else {
                    promise = this.$store.dispatch('Notes/store', this.note);
                }
                promise
                    .then(() => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$router.push({name: 'notes'});
                        }
                    })
            }
        },
        computed: {
            ...mapState('Notes', ['errors']),
            title() {
                if (this.id) {
                    return `Edit ${this.note && this.note.name.length > 0 ? this.note.name : 'note'}`;
                } else {
                    return 'Create new note';
                }
            }
        }
    }
</script>