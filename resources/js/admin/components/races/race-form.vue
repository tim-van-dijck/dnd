<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="race" class="uk-form-stacked" uk-grid>
                    <div class="uk-width-1-2">
                        <div class="uk-margin">
                            <label for="name" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('name')}">Name*</label>
                            <input id="name" title="name" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('name')}"
                                   v-model="race.name">
                        </div>
                        <div class="uk-margin">
                            <label for="size" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('size')}">Size*</label>
                            <select id="size" name="size" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('size')}"
                                    v-model="race.size">
                                <option value="">- Choose a size -</option>
                                <option v-for="size in sizes" :value="size">{{ size }}</option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="speed" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('Speed')}">Speed*</label>
                            <input id="speed" v-model="race.speed" :class="{'uk-form-danger': errors.hasOwnProperty('speed')}" class="uk-input" min="0" step="5"
                                   title="speed"
                                   type="number">
                        </div>
                        <div class="uk-margin">
                            <label for="description" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description" v-model="race.description" height="600"></html-editor>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h3>Ability Bonuses</h3>
                        <div class="uk-margin">
                            <label for="optional_ability_bonuses" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('optional_ability_bonuses')}">
                                Ability bonus choices*
                            </label>
                            <input id="optional_ability_bonuses" class="uk-input" type="number" min="0" v-model="race.optional_ability_bonuses">
                        </div>
                        <ul v-if="selected.ability_bonuses.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="ability in selected.ability_bonuses">
                                <span>
                                    {{ ability.id }} +{{ ability.bonus }}
                                    <template v-if="ability.optional">&nbsp;<em>(optional)</em></template>
                                </span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="remove('ability_bonuses', ability.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <ability-bonus-modal :selected="race.ability_bonuses.map(ability => ability.id)" @input="race.ability_bonuses.push($event)" />
                        <h3>Proficiencies</h3>
                        <div class="uk-margin">
                            <label for="optional_proficiencies" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('optional_proficiencies')}">
                                Proficiency choices*
                            </label>
                            <input id="optional_proficiencies" class="uk-input" type="number" min="0" v-model="race.optional_proficiencies">
                        </div>
                        <ul v-if="selected.proficiencies.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="proficiency in selected.proficiencies">
                                <span>
                                    {{ proficiency.name }}
                                    <template v-if="proficiency.optional">&nbsp;<em>(optional)</em></template>
                                </span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="remove('proficiencies', proficiency.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <div class="uk-margin">
                            <proficiency-select-modal :selected="race.proficiencies.map(prof => prof.id)" @input="race.proficiencies.push($event)" />
                        </div>
                        <h3>Languages</h3>
                        <div class="uk-margin">
                            <label for="optional_languages" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('optional_languages')}">
                                Language choices*
                            </label>
                            <input id="optional_languages" class="uk-input" type="number" min="0" v-model="race.optional_languages">
                        </div>
                        <ul v-if="selected.languages.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="language in selected.languages">
                                <span>
                                    {{ language.name }}
                                    <template v-if="language.optional">&nbsp;<em>(optional)</em></template>
                                </span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="remove('languages', language.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <div class="uk-margin">
                            <language-select-modal :selected="race.languages.map(lang => lang.id)" @input="race.languages.push($event)" />
                        </div>
                        <h3>Feats</h3>
                        <div class="uk-margin">
                            <label for="optional_feats" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('optional_feats')}">
                                Feat choices*
                            </label>
                            <input id="optional_feats" class="uk-input" type="number" min="0" v-model="race.optional_feats">
                        </div>
                    </div>
                    <div class="uk-margin uk-width">
                        <h2>Race Traits</h2>
                        <ul v-if="selected.traits.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="(trait, index) in selected.traits">
                                <span :title="trait.description">{{ trait.name }}</span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="removeTrait(index)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <trait-select-modal :selected="race.traits.map(trait => trait.id)" @input="race.traits.push($event)" />
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'races'}">
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

import HtmlEditor from "../../../components/partial/html-editor";
import {mapState} from "vuex";
import LanguageSelectModal from "./partial/language-select-modal";
import ProficiencySelectModal from "./partial/proficiency-select-modal";
import AbilityBonusModal from "./partial/ability-bonus-modal";
import TraitSelectModal from "./partial/trait-select-modal";
export default {
    name: "race-form",
    components: {TraitSelectModal, AbilityBonusModal, ProficiencySelectModal, LanguageSelectModal, HtmlEditor},
    created() {
        this.$store.dispatch('loadLanguages');
    },
    data() {
        return {
            errors: {},
            race: {
                name: '',
                description: '',
                size: '',
                speed: 30,
                languages: [],
                optional_languages: 0,
                proficiencies: [],
                optional_proficiencies: 0,
                ability_bonuses: [],
                optional_ability_bonuses: 0,
                optional_feats: 0,
                traits: []
            },
            sizes: [
                'Tiny',
                'Small',
                'Medium',
                'Large',
                'Huge',
                'Gargantuan',
            ]
        }
    },
    methods: {
        save() {
            this.$store.dispatch(`Races/${this.id > 0 ? 'update': 'store'}`, {id: this.id || null, race: this.race})
                .then(() => {
                    this.$router.push({name: 'races'});
                    this.$set(this, 'errors', {})
                })
                .catch((exception) => {
                    this.$set(this, 'errors', exception.response.data.errors);
                    this.$store.dispatch('Messages/error', exception.response.data.message, {root: true});
                });
        },
        remove(type, id) {
            const index = this.race?.[type]?.findIndex((item) => item.id === id)
            if (index != null) {
                this.race?.[type]?.splice(index, 1)
            }
        },
        removeTrait(index) {
            if (this.race.traits.hasOwnProperty(index)) {
                this.race.traits?.splice(index, 1)
            }
        }
    },
    computed: {
        ...mapState(['languages']),
        ...mapState('Races', ['proficiencies', 'traits']),
        title() {
            if (this.id) {
                return 'Edit ' + (this.race ? this.race.name : 'race');
            } else {
                return 'Add race';
            }
        },
        selected() {
            return {
                ability_bonuses: this.race.ability_bonuses || [],
                languages: this.race.languages?.map((lang) => {
                    const language = this.languages.find(item => item.id === lang.id)
                    return {
                        ...lang,
                        name: language.name
                    }
                }) || [],
                proficiencies: this.race.proficiencies?.map((prof) => {
                    const proficiency = this.proficiencies.find(item => item.id === prof.id)
                    return {
                        ...prof,
                        name: proficiency.name
                    }
                }) || [],
                traits: this.race.traits.map((trait) => {
                    if (trait.hasOwnProperty('id')) {
                        const selected = this.traits.find((item) => item.id === trait.id)
                        return selected || trait;
                    }
                    return trait;
                })
            }
        }
    }
}
</script>