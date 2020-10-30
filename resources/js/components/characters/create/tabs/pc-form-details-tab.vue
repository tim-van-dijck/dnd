<template>
    <div id="info-tab">
        <div uk-grid>
            <div class="uk-width-1-2">
                <div class="uk-margin">
                    <label for="name" class="uk-form-label">Name</label>
                    <input id="name" title="name" type="text" class="uk-input" v-model="info.name">
                </div>
                <div class="uk-margin">
                    <label for="race" class="uk-form-label">Race</label>
                    <select id="race" name="race" class="uk-select" v-model="info.race_id" @input="info.subrace_id = null">
                        <option :value="null">- Choose a race -</option>
                        <option v-for="race in races" :value="race.id">{{ race.name }}</option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="subrace" class="uk-form-label">Subrace</label>
                    <select id="subrace" name="subrace" class="uk-select" v-model="info.subrace_id"
                            :disabled="subraces.length == 0">
                        <option :value="null">- Choose a subrace -</option>
                        <option v-for="subrace in subraces" :value="subrace.id">{{ subrace.name }}</option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="alignment" class="uk-form-label">Alignment</label>
                    <select id="alignment" name="alignment" class="uk-select" v-model="info.alignment">
                        <option :value="null">- Choose an alignment -</option>
                        <option v-for="alignment in alignments" :value="alignment.value">{{ alignment.name }}</option>
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
                <html-editor id="bio" name="bio" v-model="info.bio"></html-editor>
            </div>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="$emit('next')">Next <i class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
    import HtmlEditor from '../../../partial/html-editor';
    import {mapState} from "vuex";

    export default {
        name: "pc-form-details-tab",
        props: ['value'],
        mounted() {
            this.$set(this, 'info', this.value);
        },
        data() {
            return {
                info: {
                    race_id: null,
                    subrace_id: null
                },
                alignments: [
                    {value: 'LG', name: 'Lawful Good'},
                    {value: 'NG', name: 'Neutral Good'},
                    {value: 'CG', name: 'Chaotic Good'},
                    {value: 'LN', name: 'Lawful Neutral'},
                    {value: 'TN', name: 'True Neutral'},
                    {value: 'CN', name: 'Chaotic Neutral'},
                    {value: 'LE', name: 'Lawful Evil'},
                    {value: 'NE', name: 'Neutral Evil'},
                    {value: 'CE', name: 'Chaotic Evil'},
                ]
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
            ...mapState('Characters', ['races', 'backgrounds']),
            subraces() {
                if (this.races)  {
                    let race = this.races[this.info.race_id];
                    if (race) {
                        return race.subraces || [];
                    }
                }
                return [];
            }
        },
        components: {HtmlEditor}
    }
</script>