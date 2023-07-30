<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="character" id="character-form" class="uk-form-stacked">
                    <div uk-grid>
                        <div class="uk-width-1-2">
                            <div class="uk-margin">
                                <label for="name" class="uk-form-label">Name</label>
                                <input id="name" title="name" type="text" class="uk-input" v-model="character.name">
                            </div>
                            <div class="uk-margin">
                                <label for="title" class="uk-form-label">Title</label>
                                <input id="title" name="title" type="text" class="uk-input" v-model="character.title">
                            </div>
                            <div class="uk-margin">
                                <label for="race" class="uk-form-label">Race</label>
                                <select id="race" name="race" class="uk-select" v-model="character.race_id">
                                    <option value="">- Choose a race -</option>
                                    <option v-for="race in races" :value="race.id">{{ race.name }}</option>
                                </select>
                            </div>
                            <div class="uk-margin" v-if="this.type !== 'player'">
                                <label for="type" class="uk-form-label">Type</label>
                                <input id="type" name="type" type="text" class="uk-input" v-model="character.type">
                            </div>
                            <div class="uk-margin">
                                <label for="age" class="uk-form-label">Age</label>
                                <input id="age" name="age" type="text" class="uk-input" v-model="character.age">
                            </div>
                            <div class="uk-margin uk-form-controls">
                                <input id="dead" name="dead" type="checkbox" class="uk-checkbox" v-model="character.dead">
                                <label for="dead">Dead</label>
                            </div>
                            <hr>
                            <div class="uk-margin uk-form-controls">
                                <input id="private" name="private" type="checkbox" class="uk-checkbox" v-model="character.private">
                                <label for="private">Private</label>
                            </div>
                        </div>
                        <div class="uk-width-1-2">
                            <label for="bio" class="uk-form-label">Bio</label>
                            <editor id="bio" name="bio" :init="init" v-model="character.bio"></editor>
                        </div>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: type == 'player' ? 'player-characters' : 'npcs'}">
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
    import Editor from '@tinymce/tinymce-vue'
    import {mapState} from 'vuex';

    export default {
        name: "character-form",
        props: ['id', 'type'],
        created() {
            this.$store.dispatch('Characters/loadRaces');
            this.character = {
                type: this.type == 'player' ? this.type : ''
            };
            if (this.id) {
                this.$store.dispatch('Characters/loadCharacter', {campaign_id: 1, id: this.id})
                    .then((character) => {
                        this.character = JSON.parse(JSON.stringify(character));
                    });
            }
        },
        data() {
            return {
                character: null
            }
        },
        methods: {
            save() {
                let data = {campaign_id: 1, character: this.character};
                if (this.id) {
                    data.id = this.id;
                    this.$store.dispatch('Characters/updateCharacter', data);
                } else {
                    this.$store.dispatch('Characters/storeCharacter', data);
                }
            }
        },
        computed: {
            ...mapState('Characters', ['characters', 'errors', 'races']),
            init() {
                return {
                    height: 400,
                    plugins: [
                        'link', 'template', 'hr', 'anchor', 'fullscreen',
                        'searchreplace', 'autolink', 'table'
                    ],
                    toolbar1: 'formatselect | bold italic underline strikethrough forecolor backcolor | link table | alignleft aligncenter alignright  | numlist bullist outdent indent | removeformat',
                    init_instance_callback: function(editor) {
                        var freeTiny = document.querySelector('.tox-notifications-container');
                        freeTiny.style.display = 'none';
                    }
                }
            },
            title() {
                if (this.id) {
                    return 'Edit ' + (this.character ? this.character.name : 'character');
                } else {
                    return 'Add character';
                }
            }
        },
        components: {
            Editor
        }
    }
</script>