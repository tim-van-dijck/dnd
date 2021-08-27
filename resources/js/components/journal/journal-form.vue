<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="entry" action="">
                    <div class="uk-margin">
                        <label for="title" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('title')}">Title*</label>
                        <input id="title" name="title" type="text" class="uk-input"
                               :class="{'uk-form-danger': errors.hasOwnProperty('title')}"
                               v-model="entry.title">
                    </div>
                    <div class="uk-margin">
                        <label for="entry-content" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('content')}">Content*</label>
                        <html-editor id="entry-content" name="entry-content" height="800" v-model="entry.content"></html-editor>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'journal'}">
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
    name: "journal-form",
    props: ['id'],
    components: {HtmlEditor},
    created() {
        if (this.id) {
            this.$store.dispatch('Journal/find', this.id)
                .then((entry) => {
                    this.entry = JSON.parse(JSON.stringify(entry));
                });
        } else {
            this.entry = {};
        }
    },
    data() {
        return {
            entry: null,
            tab: 'details'
        }
    },
    methods: {
        save() {
            let promise;
            let entry = {
                title: this.entry.title,
                content: this.entry.content
            };

            if (this.id) {
                promise = this.$store.dispatch('Journal/update', {id: this.id, entry});
            } else {
                promise = this.$store.dispatch('Journal/store', entry);
            }
            promise
                .then(() => {
                    if (Object.keys(this.errors || {}).length === 0) {
                        this.$router.push({name: 'journal'});
                    }
                });
        }
    },
    computed: {
        ...mapState('Journal', ['errors']),
        title() {
            if (this.id) {
                return `Edit ${this.entry && this.entry.title.length > 0 ? this.entry.title : 'entry'}`;
            } else {
                return 'Create new entry';
            }
        }
    }
}
</script>