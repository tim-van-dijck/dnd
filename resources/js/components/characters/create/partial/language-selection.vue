<template>
    <div class="languages">
        <div class="uk-accordion-title"><h2>Languages</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-child-width-1-4@m uk-child-width-1-3@s uk-grid-small uk-grid-match"
                 v-if="languages.known.length + selection.length > 0" uk-grid>
                <div v-for="language in languages.known">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ language.name }}</div>
                        <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                    </div>
                </div>
                <div v-for="(language) in selectedLanguages">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ language.name }}</div>
                        <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                @click.prevent="removeLanguage(language.id)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                    </div>
                </div>
            </div>
            <div class="uk-margin" v-if="optionalLanguages > 0 && optionalLanguages - selection.length > 0">
                <label for="language">Choose {{ optionalLanguages - selection.length }} languages</label>
                <select name="language" id="language" class="uk-select" @input="addLanguage">
                    <option :value="null">- Make a choice -</option>
                    <option v-for="language in languages.optional" :value="language.id"
                            :disabled="languages.known.includes(language.id)">
                        {{ language.name }}
                    </option>
                </select>
            </div>
            <div class="uk-alert uk-alert-warning" v-if="languages.known.length === 0 && optionalLanguages === 0">
                <p>There are no languages available. Have you selected a race yet?</p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "language-selection",
        props: ['background', 'race', 'subrace', 'value'],
        created() {
            this.$store.dispatch('loadLanguages')
                .then(() => {
                    if ((this.value || []).length > 0) {
                        for (let language of this.value) {
                            let known = this.languages.known.find((item) => item.id == parseInt(language));
                            if (known == null) {
                                this.selection.push(parseInt(language));
                            }
                        }
                    }
                })
        },
        data() {
            return {
                selection: []
            }
        },
        methods: {
            addLanguage($event) {
                this.selection.splice(this.selection.length, 1, parseInt($event.target.value));
                $event.target.value = '';
            },
            removeLanguage(languageId) {
                this.selection.splice(this.selection.indexOf(languageId), 1);
            }
        },
        computed: {
            ...mapState({'availableLanguages': 'languages'}),
            languages() {
                let languages = {
                    known: [],
                    optional: []
                };
                if (this.race) {
                    for (let language of this.race.languages) {
                        if (language.optional) {
                            if (!this.selection.includes(language.id)) {
                                languages.optional.push(language);
                            }
                        } else {
                            languages.known.push(language);
                        }
                    }

                    if (this.subrace) {
                        for (let language of this.subrace.languages) {
                            if (language.optional) {
                                let alreadyChosen = this.selection.includes(language.id);
                                let alreadyKnown = languages.known.find(item => item.id === language.id) != null;
                                if (!alreadyChosen && !alreadyKnown) {
                                    languages.optional.push(language);
                                }
                            } else {
                                languages.known.push(language);
                            }
                        }
                    }
                }

                if (this.background && this.background.language_choices > 0) {
                    let backgroundChoices = 0;
                    for (let selection of this.selection) {
                        if (this.race && (this.race.languages || []).find(item => item.id === selection)) {
                            continue;
                        }
                        if (this.subrace && (this.subrace.languages || []).find(item => item.id === selection)) {
                            continue;
                        }
                        backgroundChoices++;
                    }
                    if (backgroundChoices < this.background.language_choices) {
                        languages.optional = (this.availableLanguages || [])
                            .filter((language) => {
                                let notKnown = languages.known.find(item => item.id === language.id) == null
                                return !this.selection.includes(language.id) && notKnown;
                            });
                    }
                }
                return languages;
            },
            optionalLanguages() {
                let amount = 0;
                if (this.race) {
                    amount += this.race.optional_languages;
                }
                if (this.subrace) {
                    amount += this.subrace.optional_languages;
                }
                if (this.background) {
                    amount += this.background.language_choices;
                }
                return amount;
            },
            selectedLanguages() {
                let known = [];
                for (let languageId of this.selection) {
                    known.push(this.availableLanguages.find(language => language.id === languageId));
                }
                return known;
            }
        },
        watch: {
            selection: {
                deep: true,
                handler() {
                    this.$emit('input', this.selection);
                }
            }
        }
    }
</script>