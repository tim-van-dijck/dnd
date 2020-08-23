<template>
    <div id="pc-form">
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="character" id="character-form" class="uk-form-stacked">
                    <ul class="uk-tab">
                        <li :class="{'uk-active': tab == 'info'}">
                            <a @click.prevent="tab = 'info'">Info</a>
                        </li>
                        <li :class="{'uk-active': tab == 'class'}">
                            <a @click.prevent="tab = 'class'">Class</a>
                        </li>
                        <li :class="{'uk-active': tab == 'proficiency'}">
                            <a @click.prevent="tab = 'proficiency'">Languages, Skills & Proficiencies</a>
                        </li>
                        <li :class="{'uk-active': tab == 'ability'}">
                            <a @click.prevent="tab = 'ability'">Abilities</a>
                        </li>
                        <li :class="{'uk-active': tab == 'ideal'}">
                            <a @click.prevent="tab = 'ideal'">Personality</a>
                        </li>
                    </ul>
                    <pc-form-info-tab v-show="tab == 'info'" v-model="character.info" />
                    <pc-form-class-tab v-show="tab == 'class'" v-model="character.classes" />
                    <pc-form-proficiency-tab v-show="tab == 'proficiency'" v-model="character.proficiencies"
                        :info="character.info" :character-classes="character.classes" />
                    <pc-form-abilities-tab v-show="tab == 'ability'" v-model="character.ability_scores"
                        :info="character.info" :character-classes="character.classes" />
                    <pc-form-personality-tab v-show="tab == 'ideal'" v-model="character.personality" />

                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
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
    import {mapState} from 'vuex';
    import PcFormInfoTab from "./tabs/pc-form-info-tab";
    import PcFormClassTab from "./tabs/pc-form-class-tab";
    import PcFormProficiencyTab from "./tabs/pc-form-proficiency-tab";
    import PcFormPersonalityTab from "./tabs/pc-form-personality-tab";
    import PcFormAbilitiesTab from "./tabs/pc-form-abilities-tab";

    export default {
        name: "character-form",
        props: ['id', 'type'],
        created() {
            this.$store.dispatch('Characters/loadRaces');
            this.$store.dispatch('Characters/loadClasses');
            this.character = {
                type: 'player',
                ability_scores: {},
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
                proficiencies: {}
            };
            if (this.id) {
                this.$store.dispatch('Characters/find', this.id)
                    .then((character) => {
                        this.character = JSON.parse(JSON.stringify(character));
                    });
            }
        },
        data() {
            return {
                character: null,
                tab: 'info'
            }
        },
        methods: {
            save() {
                if (this.id) {
                    let payload = {id: this.id, character: this.character}
                    this.$store.dispatch('Characters/update', payload)
                        .then((response) => {
                            this.$router.push({name: 'pc-detail', params: {id: response.data.data.id}});
                        });
                } else {
                    this.$store.dispatch('Characters/store', this.character)
                        .then(() => {
                            this.$router.push({name: 'player-characters'});
                        });
                }
            }
        },
        computed: {
            ...mapState('Characters', ['characters', 'errors']),
            title() {
                if (this.id) {
                    return 'Edit ' + (this.character ? this.character.name : 'character');
                } else {
                    return 'Add character';
                }
            }
        },
        components: {
            PcFormAbilitiesTab,
            PcFormPersonalityTab,
            PcFormProficiencyTab,
            PcFormClassTab,
            PcFormInfoTab,
        }
    }
</script>