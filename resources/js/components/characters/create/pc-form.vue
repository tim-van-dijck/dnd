<template>
    <div id="pc-form">
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="character && Object.keys(classes).length > 0 && Object.keys(races).length > 0" id="character-form" class="uk-form-stacked">
                    <pc-form-navigation :character="character" :spellcaster="spellcaster" :tab="tab" :errors="errors" @navigate="goToTab" />

                    <pc-form-details-tab v-show="tab === 'details'" v-model="character.info" @next="goToTab('class')" />
                    <pc-form-class-tab v-show="tab === 'class'" v-model="character.classes" @next="goToTab('background')" />
                    <pc-form-background-tab v-show="tab === 'background'" v-model="character.background_id" @next="goToTab('proficiency')" />
                    <pc-form-proficiency-tab v-show="tab === 'proficiency'" v-model="character.proficiencies"
                                             :info="character.info" :character-classes="character.classes"
                                             :background-id="character.background_id" @next="goToTab('ability')" />
                    <pc-form-abilities-tab v-show="tab === 'ability'" v-model="character.ability_scores"
                                           :info="character.info" :character-classes="character.classes" @next="goToTab('personality')" />
                    <pc-form-personality-tab v-show="tab === 'personality'" :spellcaster="spellcaster"
                                             v-model="character.personality" @next="nextOrSave(spellcaster, 'spells')" />
                    <pc-form-spell-tab v-if="spellcaster" v-show="tab === 'spells'" v-model="character.spells"
                                       :info="character.info" :character-classes="character.classes" @next="save" />
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
    import CharacterFormatter from "../../../lib/CharacterFormatter";
    import PcFormDetailsTab from "./tabs/pc-form-details-tab";
    import PcFormClassTab from "./tabs/pc-form-class-tab";
    import PcFormProficiencyTab from "./tabs/pc-form-proficiency-tab";
    import PcFormPersonalityTab from "./tabs/pc-form-personality-tab";
    import PcFormAbilitiesTab from "./tabs/pc-form-abilities-tab";
    import PcFormSpellTab from "./tabs/pc-form-spell-tab";
    import PcFormNavigation from "./partial/pc-form-navigation";
    import PcFormBackgroundTab from "./tabs/pc-form-background-tab";

    export default {
        name: "pc-form",
        props: ['id', 'type'],
        created() {
            this.$store.dispatch('Characters/loadRaces');
            this.$store.dispatch('Characters/loadClasses');
            if (this.id) {
                this.$store.dispatch('Characters/find', this.id)
                    .then((character) => {
                        this.$set(this, 'character', CharacterFormatter.format(character));
                    });
            } else {
                this.character = {
                    type: 'player',
                    ability_scores: {
                        STR: 3,
                        DEX: 3,
                        CON: 3,
                        INT: 3,
                        WIS: 3,
                        CHA: 3
                    },
                    info: {
                        race_id: null,
                        subrace_id: null,
                        alignment: null
                    },
                    classes: [],
                    personality: {
                        trait: '',
                        ideal: '',
                        bond: '',
                        flaw: ''
                    },
                    proficiencies: {},
                    background_id: null
                };
            }
        },
        mounted() {
            this.$store.commit('Characters/SET_ERRORS', {});
        },
        data() {
            return {
                character: null,
                tab: 'details'
            }
        },
        methods: {
            nextOrSave(condition, next) {
                if (condition) {
                    this.goToTab(next);
                } else {
                    this.save();
                }
            },
            save() {
                let promise;
                if (this.id) {
                    let payload = {id: this.id, character: this.character}
                    promise = this.$store.dispatch('Characters/update', payload);
                } else {
                    promise = this.$store.dispatch('Characters/store', this.character);
                }
                promise.then((character) => {
                    if (Object.keys(this.errors).length === 0) {
                        this.$router.push({name: 'pc-detail', params: {id: character.id}});
                    }
                });
            },
            goToTab(tab) {
                this.tab = tab;
            }
        },
        computed: {
            ...mapState('Characters', ['characters', 'errors', 'classes', 'races']),
            title() {
                if (this.id) {
                    return `Edit ${this.character ? this.character.info.name : 'character'}`;
                } else {
                    return 'Add character';
                }
            },
            spellcaster() {
                if (this.character.classes.length === 0 || Object.keys(this.classes).length === 0) {
                    return false;
                }

                for (let characterClass of this.character.classes) {
                    if (characterClass.class_id) {
                        let chosenClass = this.classes[characterClass.class_id];
                        if (chosenClass.spellcaster) {
                            return true;
                        }
                        let subclass = chosenClass.subclasses.find(item => item.id == characterClass.subclass_id);
                        if (subclass && subclass.spellcaster) {
                            return true;
                        }
                    }
                }
                return false;
            }
        },
        components: {
            PcFormBackgroundTab,
            PcFormNavigation,
            PcFormSpellTab,
            PcFormAbilitiesTab,
            PcFormPersonalityTab,
            PcFormProficiencyTab,
            PcFormClassTab,
            PcFormDetailsTab,
        }
    }
</script>