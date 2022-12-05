<template>
    <div>
        <h1>{{ ui.title.value }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.race" class="uk-form-stacked" uk-grid>
                    <div class="uk-width-1-2">
                        <div class="uk-margin">
                            <label for="name" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('name')}">Name*</label>
                            <input id="name" title="name" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('name')}"
                                   v-model="state.race.name">
                        </div>
                        <div class="uk-margin">
                            <label for="size" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('size')}">Size*</label>
                            <select id="size" name="size" class="uk-input"
                                    :class="{'uk-form-danger': state.errors.hasOwnProperty('size')}"
                                    v-model="state.race.size">
                                <option value="">- Choose a size -</option>
                                <option v-for="size in state.sizes" :value="size">{{ size }}</option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="speed" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('Speed')}">Speed*</label>
                            <input id="speed" v-model="state.race.speed"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('speed')}" class="uk-input"
                                   min="0"
                                   step="5"
                                   title="speed"
                                   type="number">
                        </div>
                        <div class="uk-margin">
                            <label for="description" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('description')}">Description</label>
                            <html-editor id="description" name="description" v-model="state.race.description"
                                         height="600"></html-editor>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <h3>Ability Bonuses</h3>
                        <div class="uk-margin">
                            <label for="optional_ability_bonuses" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('optional_ability_bonuses')}">
                                Ability bonus choices*
                            </label>
                            <input id="optional_ability_bonuses" class="uk-input" type="number" min="0"
                                   v-model="state.race.optional_ability_bonuses">
                        </div>
                        <ul v-if="ui.selected.value.ability_bonuses.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="ability in ui.selected.value.ability_bonuses">
                                <span>
                                    {{ ability.ability }} +{{ ability.bonus }}
                                    <template v-if="ability.optional">&nbsp;<em>(optional)</em></template>
                                </span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="state.remove('ability_bonuses', ability.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <ability-bonus-modal :selected="state.race.ability_bonuses.map(ability => ability.id)"
                                             @input="state.race.ability_bonuses.push($event)"/>
                        <h3>Proficiencies</h3>
                        <div class="uk-margin">
                            <label for="optional_proficiencies" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('optional_proficiencies')}">
                                Proficiency choices*
                            </label>
                            <input id="optional_proficiencies" class="uk-input" type="number" min="0"
                                   v-model="state.race.optional_proficiencies">
                        </div>
                        <ul v-if="ui.selected.value.proficiencies.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="proficiency in ui.selected.value.proficiencies">
                                <span>
                                    {{ proficiency.name }}
                                    <template v-if="proficiency.optional">&nbsp;<em>(optional)</em></template>
                                </span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="state.remove('proficiencies', proficiency.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <div class="uk-margin">
                            <proficiency-select-modal :selected="state.race.proficiencies.map(prof => prof.id)"
                                                      @input="state.race.proficiencies.push($event)"/>
                        </div>
                        <h3>Languages</h3>
                        <div class="uk-margin">
                            <label for="optional_languages" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('optional_languages')}">
                                Language choices*
                            </label>
                            <input id="optional_languages" class="uk-input" type="number" min="0"
                                   v-model="state.race.optional_languages">
                        </div>
                        <ul v-if="ui.selected.value.languages.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="language in ui.selected.value.languages">
                                <span>
                                    {{ language.name }}
                                    <template v-if="language.optional">&nbsp;<em>(optional)</em></template>
                                </span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="state.remove('languages', language.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <div class="uk-margin">
                            <language-select-modal :selected="state.race.languages.map(lang => lang.id)"
                                                   @input="state.race.languages.push($event)"/>
                        </div>
                        <h3>Feats</h3>
                        <div class="uk-margin">
                            <label for="optional_feats" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('optional_feats')}">
                                Feat choices*
                            </label>
                            <input id="optional_feats" class="uk-input" type="number" min="0"
                                   v-model="state.race.optional_feats">
                        </div>
                    </div>
                    <div class="uk-margin uk-width">
                        <h2>Race Traits</h2>
                        <ul v-if="ui.selected.value.traits.length > 0">
                            <li class="uk-flex uk-flex-between" v-for="(trait, index) in ui.selected.value.traits">
                                <span :title="trait.description">{{ trait.name }}</span>
                                <button class="uk-button uk-button-link uk-text-danger"
                                        @click.prevent="state.removeTrait(index)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </li>
                        </ul>
                        <trait-select-modal :selected="state.race.traits.map(trait => trait.id)"
                                            @input="state.addTrait($event)"/>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary uk-margin-right" @click.prevent="state.save">Save
                        </button>
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

import { useMainStore } from '@admin/stores/main'
import { useRaceStore } from '@admin/stores/races'
import HtmlEditor from '@components/partial/html-editor'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, onMounted } from 'vue'
import AbilityBonusModal from './components/ability-bonus-modal/ability-bonus-modal'
import LanguageSelectModal from './components/language-select-modal'
import ProficiencySelectModal from './components/proficiency-select-modal/proficiency-select-modal'
import TraitSelectModal from './components/trait-select-modal/trait-select-modal'
import { useState } from './race-form.state'

export default {
    name: 'race-form',
    props: ['id'],
    components: { TraitSelectModal, AbilityBonusModal, ProficiencySelectModal, LanguageSelectModal, HtmlEditor },
    setup(props) {
        const mainStore = useMainStore()
        const store = useRaceStore()
        const { proficiencies, traits } = storeToRefs(store)
        const { languages } = storeToRefs(mainStore)
        const state = useState(store, props)

        const selected = computed(() => (
            {
                ability_bonuses: state.race.ability_bonuses || [],
                languages: state.race.languages?.map((lang) => {
                    const language = languages.value.find(item => item.id === lang.id) || {}
                    return {
                        ...lang,
                        name: language.name
                    }
                }) || [],
                proficiencies: state.race.proficiencies?.map((prof) => {
                    const proficiency = proficiencies.value.find(item => item.id === prof.id)
                    return {
                        ...prof,
                        name: proficiency.name
                    }
                }) || [],
                traits: state.race.traits.map((trait) => {
                    if (trait.hasOwnProperty('id')) {
                        const selected = traits.value.find((item) => item.id === trait.id)
                        return selected || trait
                    }
                    return trait
                })
            }
        ))
        const title = computed(() => state.race.id ? `Edit ${state.race.name || 'race'}` : 'Add race')

        onMounted(() => {
            mainStore.loadLanguages()
            if (props.id) {
                store.find(props.id)
                    .then((race) => {
                        state.setRace(race)
                    })
            }
        })

        return {
            state,
            ui: {
                selected,
                title
            }
        }
    }
}
</script>