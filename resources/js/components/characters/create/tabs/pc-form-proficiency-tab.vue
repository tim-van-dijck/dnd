<template>
    <div id="proficiency-tab">
        <div uk-accordion="multiple: true;">
            <language-selection class="uk-open"
                                :background="background"
                                :race="race"
                                :subrace="subrace"
                                v-model="choices.languages" />
            <skill-selection class="uk-accordion-content uk-open"
                             :background="background"
                             :classes="characterClasses"
                             :race="race"
                             :subrace="subrace"
                             v-model="choices.skills" />
            <tool-selection class="uk-open"
                            :background="background"
                            :classes="characterClasses"
                            :race="race"
                            :subrace="subrace"
                            v-model="choices.tools" />
            <instrument-selection class="uk-open"
                                  :background="background"
                                  :classes="characterClasses"
                                  :race="race"
                                  :subrace="subrace"
                                  v-model="choices.instruments" />
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
    import {mapState} from "vuex";
    import SkillSelection from "../partial/skill-selection";
    import ToolSelection from "../partial/tool-selection";
    import InstrumentSelection from "../partial/instrument-selection";
    import LanguageSelection from "../partial/language-selection";

    export default {
        name: "pc-form-proficiency-tab",
        components: {LanguageSelection, InstrumentSelection, ToolSelection, SkillSelection},
        props: ['backgroundId', 'characterClasses', 'info', 'value'],
        data() {
            return {
                choices: {
                    languages: [],
                    instruments: [],
                    skills: [],
                    tools: []
                }
            }
        },
        computed: {
            ...mapState('Characters', ['backgrounds', 'classes', 'races']),
            race() {
                return this.info.race_id ? this.races[this.info.race_id] || null : null;
            },
            subrace() {
                return this.race && this.info.subrace_id ?
                    this.race.subraces.find(item => item.id == this.info.subrace_id) : null;
            },
            background() {
                if (!this.backgroundId || ! this.backgrounds) {
                    return null;
                }
                return this.backgrounds.find(item => item.id == this.backgroundId);
            }
        },
        watch: {
            'info.race_id': function (value, oldValue) {
                if (oldValue != value || oldValue != value) {
                    this.$set(this.choices, 'languages', []);
                }
            },
            'info.subrace_id': function (value, oldValue) {
                if (oldValue != value || oldValue != value) {
                    this.$set(this.choices, 'languages', []);
                }
            },
            'background_id': function (value, oldValue) {
                if (oldValue != value || oldValue != value) {
                    this.$set(this.choices, 'languages', []);
                }
            },
            choices: {
                deep: true,
                handler() {
                    this.$emit('input', this.choices);
                }
            }
        }
    }
</script>

<style scoped>

</style>