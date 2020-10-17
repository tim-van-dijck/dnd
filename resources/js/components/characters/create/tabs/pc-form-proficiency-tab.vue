<template>
    <div id="proficiency-tab" uk-accordion="multiple: true; active: 0">
        <div class="languages">
            <div class="uk-accordion-title"><h2>Languages</h2></div>
            <div class="uk-accordion-content">
                <div class="uk-child-width-1-4@m uk-grid-small uk-grid-match"
                     v-if="languages.known.length + choices.languages.length > 0" uk-grid>
                    <div v-for="language in languages.known">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ language.name }}</div>
                            <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                        </div>
                    </div>
                    <div v-for="languageId in choices.languages">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ languages.optional.find(item => item.id == languageId).name }}</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="choices.languages.splice(choices.languages.indexOf(languageId), 1)">
                                <i class="fas fa-trash"></i>
                            </button>
                            <p>
                                <em>
                                    {{ languages.optional.find(item => item.id == languageId).script ? languages.optional.find(item => item.id == languageId).script : 'No' }} script
                                </em>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="uk-margin" v-if="optionalLanguages > 0 && optionalLanguages - choices.languages.length > 0">
                    <label for="language">Choose {{ optionalLanguages - choices.languages.length }} languages</label>
                    <select name="language" id="language" class="uk-select" @input="choices.languages.splice(choices.languages.length, 1, $event.target.value)">
                        <option :value="null">- Make a choice -</option>
                        <option v-for="language in languages.optional" :value="language.id"
                                :disabled="languages.known.includes(language.id)">
                            {{ language.name }}
                        </option>
                    </select>
                </div>
                <div class="uk-alert uk-alert-warning" v-if="languages.known.length == 0 && optionalLanguages == 0">
                    <p>There are no languages available. Have you selected a race yet?</p>
                </div>
            </div>
        </div>
        <skill-selection class="uk-accordion-content"
                         :race="race"
                         :subrace="subrace"
                         :classes="characterClasses"
                         v-model="choices.skills" />
        <tool-selection :race="race" :subrace="subrace" :classes="characterClasses" v-model="choices.tools" />
        <instrument-selection :race="race" :subrace="subrace" :classes="characterClasses" v-model="choices.instruments" />
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import SkillSelection from "../partial/skill-selection";
    import ToolSelection from "../partial/tool-selection";
    import InstrumentSelection from "../partial/instrument-selection";

    export default {
        name: "pc-form-proficiency-tab",
        components: {InstrumentSelection, ToolSelection, SkillSelection},
        props: ['characterClasses', 'info', 'value'],
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
            ...mapState('Characters', ['classes', 'races']),
            languages() {
                let languages = {
                    known: [],
                    optional: []
                };
                if (this.race) {
                    for (let language of this.race.languages) {
                        if (language.optional) {
                            languages.optional.push(language);
                        } else {
                            languages.known.push(language);
                        }
                    }

                    if (this.subrace) {
                        for (let language of this.subrace.languages) {
                            if (language.optional) {
                                languages.optional.push(language);
                            } else {
                                languages.known.push(language);
                            }
                        }
                    }
                }
                return languages;
            },
            race() {
                return this.info.race_id ? this.races[this.info.race_id] || null : null;
            },
            subrace() {
                return this.race && this.info.subrace_id ?
                    this.race.subraces.find(item => item.id == this.info.subrace_id) : null;
            },
            optionalLanguages() {
                let amount = 0;
                if (this.race) {
                    amount += this.race.optional_languages;
                }
                if (this.subrace) {
                    amount += this.subrace.optional_languages;
                }
                return amount;
            },
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