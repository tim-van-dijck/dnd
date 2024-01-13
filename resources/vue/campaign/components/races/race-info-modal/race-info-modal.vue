<template>
    <div id="race-list" v-if="info.races.value">
        <button class="uk-button uk-button-secondary" @click.prevent="ui.open">Race list</button>
        <div id="race-info-modal" uk-modal>
            <div class="uk-width-expand uk-modal-dialog uk-modal-body">
                <h2 v-if="info.activeRace.value" class="uk-modal-title">{{ info.activeRace.value.name }}</h2>
                <h2 v-else class="uk-modal-title">Races</h2>
                <div uk-grid>
                    <div class="uk-width-1-5">
                        <ul class="uk-nav uk-nav-default">
                            <template v-for="race in info.races.value">
                                <li :class="{'uk-active': state.active.race === race?.id}">
                                    <a href="#" @click.prevent="state.setActive(race)">
                                        {{ race?.name }}
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <div class="uk-width-4-5">
                        <div v-if="info.activeRace.value">
                            <ul uk-tab>
                                <li :class="{'uk-active': state.active.tab === 'description'}">
                                    <a href="#" @click.prevent="state.setActiveTab('description')">Description</a>
                                </li>
                                <li v-if="info.activeRace.value.traits.length > 0"
                                    :class="{'uk-active': state.active.tab === 'traits'}">
                                    <a href="#" @click.prevent="state.setActiveTab('traits')">Traits</a>
                                </li>
                                <li v-if="info.activeRace.value.subraces.length > 0"
                                    :class="{'uk-active': state.active.tab === 'subraces'}">
                                    <a href="#" @click.prevent="state.setActiveTab('subraces')">Subraces</a>
                                </li>
                            </ul>
                            <div v-show="state.active.tab === 'description'">
                                <ul class="uk-list">
                                    <li><b>Ability Scores:</b> {{ info.activeRace.value.ability_scores }}</li>
                                    <li v-if="info.activeRace.value.proficiencies.skills.length > 0"><b>Skills:</b>
                                        {{ info.activeRace.value.proficiencies.skills.join(', ') }}
                                    </li>
                                    <li v-if="info.activeRace.value.proficiencies.tools.length > 0"><b>Tools:</b>
                                        {{ info.activeRace.value.proficiencies.tools.join(', ') }}
                                    </li>
                                    <li v-if="info.activeRace.value.proficiencies.weapons.length > 0"><b>Weapons:</b>
                                        {{ info.activeRace.value.proficiencies.weapons.join(', ') }}
                                    </li>
                                    <li v-if="info.activeRace.value.proficiencies.armor.length > 0"><b>Armor:</b>
                                        {{ info.activeRace.value.proficiencies.armor.join(', ') }}
                                    </li>
                                    <li v-if="info.activeRace.value.optional_proficiencies > 0">
                                        <b>Choose {{ info.activeRace.value.optional_proficiencies }} of:</b>
                                        {{ info.activeRace.value.proficiencies.optional.join(', ') }}
                                    </li>
                                </ul>
                                <h3>Race description</h3>
                                <div v-html="info.activeRace.value.description" uk-overflow-auto></div>
                            </div>
                            <div v-if="info.activeRace.value.traits.length > 0" v-show="state.active.tab === 'traits'"
                                 uk-grid>
                                <div class="uk-width-1-3">
                                    <ul class="uk-nav uk-nav-default">
                                        <template v-for="trait in info.activeRace.value.traits">
                                            <li :class="{'uk-active': state.active.trait === trait.id}">
                                                <a href="#" @click.prevent="state.active.trait = trait.id">
                                                    {{ trait.name }}
                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <div class="uk-width-2-3 class-specs">
                                    <h4>{{ info.activeTrait.value.name }}</h4>
                                    <div v-html="info.activeTrait.value.description"></div>
                                </div>
                            </div>
                            <div v-show="state.active.tab === 'subraces'">
                                <ul uk-tab>
                                    <template v-for="subrace in info.activeRace.value.subraces">
                                        <li :class="{'uk-active': state.active.subrace === subrace.id}">
                                            <a href="#"
                                               @click.prevent="state.setActiveSubrace(subrace.id)">{{
                                                    subrace.name
                                                }}</a>
                                        </li>
                                    </template>
                                </ul>
                                <template v-for="subrace in info.activeRace.value.subraces">
                                    <div v-if="state.active.subrace === subrace.id" class="subrace-info">
                                        <div class="class-description" v-html="subrace.description"></div>
                                        <ul class="uk-list">
                                            <li v-if="info.activeSubrace.value.ability_scores.length > 0"><b>Ability
                                                Scores:</b>
                                                {{ info.activeSubrace.value.ability_scores }}
                                            </li>
                                            <li v-if="info.activeSubrace.value.proficiencies.skills.length > 0">
                                                <b>Skills:</b>
                                                {{ info.activeSubrace.value.proficiencies.skills.join(', ') }}
                                            </li>
                                            <li v-if="info.activeSubrace.value.proficiencies.tools.length > 0">
                                                <b>Tools:</b>
                                                {{ info.activeSubrace.value.proficiencies.tools.join(', ') }}
                                            </li>
                                            <li v-if="info.activeSubrace.value.proficiencies.weapons.length > 0">
                                                <b>Weapons:</b>
                                                {{ info.activeSubrace.value.proficiencies.weapons.join(', ') }}
                                            </li>
                                            <li v-if="info.activeSubrace.value.proficiencies.armor.length > 0">
                                                <b>Armor:</b>
                                                {{ info.activeSubrace.value.proficiencies.armor.join(', ') }}
                                            </li>
                                            <li v-if="info.activeSubrace.value.optional_proficiencies > 0">
                                                <b>Choose {{ info.activeSubrace.value.optional_proficiencies }} of:</b>
                                                {{ info.activeSubrace.value.proficiencies.optional.join(', ') }}
                                            </li>
                                        </ul>
                                        <h3>Traits</h3>
                                        <div uk-grid>
                                            <div class="uk-width-1-3">
                                                <ul class="uk-nav uk-nav-default">
                                                    <li :class="{'uk-active': state.active.subTrait === trait.id}"
                                                        v-for="trait in info.activeSubrace.value.traits">
                                                        <a href="#" @click.prevent="active.subTrait = trait.id">
                                                            {{ trait.name }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div v-if="info.activeSubraceTrait.value" class="uk-width-2-3">
                                                <h4>{{ info.activeSubraceTrait.value.name }}</h4>
                                                <div v-html="info.activeSubraceTrait.value.description"></div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <p class="uk-text-center" v-else><i class="fas fa-2x fa-sync fa-spin"></i></p>
                    </div>
                </div>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close/>
            </div>
        </div>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { onMounted, watch } from 'vue'
import { useCharacterStore } from '../../../stores/characters'
import { useRaceInfoModalComputed, useRaceInfoModalState } from './race-info-modal.state'

export default {
    name: 'race-info-modal',
    setup() {
        const store = useCharacterStore()
        const { races } = storeToRefs(store)
        const state = useRaceInfoModalState(races)
        const info = useRaceInfoModalComputed(races, state.active)

        onMounted(() => {
            if (races?.length > 0) {
                const race = races.value?.[0]
                if (race) {
                    state.setActive({ ...race })
                }
            }
        })

        watch(store, () => {
            const race = races.value?.[0]
            if (race) {
                state.setActive({ ...race })
            }
        })

        return {
            info,
            state,
            ui: {
                open: () => UIKit.modal('#race-info-modal').show()
            }
        }
    }
}
</script>