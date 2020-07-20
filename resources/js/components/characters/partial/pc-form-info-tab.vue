<template>
    <div uk-grid>
        <div class="uk-width-1-2">
            <div class="uk-margin">
                <label for="name" class="uk-form-label">Name</label>
                <input id="name" title="name" type="text" class="uk-input" v-model="info.name">
            </div>
            <div class="uk-margin">
                <label for="title" class="uk-form-label">Title</label>
                <input id="title" name="title" type="text" class="uk-input" v-model="info.title">
            </div>
            <div class="uk-margin">
                <label for="race" class="uk-form-label">Race</label>
                <select id="race" name="race" class="uk-select" v-model="info.race_id">
                    <option value="">- Choose a race -</option>
                    <option v-for="race in races" :value="race.id">{{ race.name }}</option>
                </select>
            </div>
            <div class="uk-margin">
                <label for="age" class="uk-form-label">Age</label>
                <input id="age" name="age" type="text" class="uk-input" v-model="info.age">
            </div>
            <div class="uk-margin uk-form-controls">
                <input id="dead" name="dead" type="checkbox" class="uk-checkbox" v-model="info.dead">
                <label for="dead">Dead</label>
            </div>
            <hr>
            <div class="uk-margin uk-form-controls">
                <input id="private" name="private" type="checkbox" class="uk-checkbox" v-model="info.private">
                <label for="private">Private</label>
            </div>
        </div>
        <div class="uk-width-1-2">
            <label for="bio" class="uk-form-label">Bio</label>
            <editor id="bio" name="bio" :init="init" v-model="info.bio"></editor>
        </div>
    </div>
</template>

<script>
    import Editor from '@tinymce/tinymce-vue'
    import {mapState} from "vuex";

    export default {
        name: "pc-form-info-tab",
        props: ['value'],
        mounted() {
            this.$store.dispatch('Characters/loadRaces');
            this.info = this.value;
        },
        data() {
            return {
                info: {}
            }
        },
        watch: {
            info: {
                deep: true,
                handler() {
                    this.$emit('input', this.info);
                }
            }
        },
        computed: {
            ...mapState('Characters', ['races']),
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
            }
        },
        components: {Editor}
    }
</script>