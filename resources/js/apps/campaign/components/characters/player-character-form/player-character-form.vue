<template>
    <div id="pc-form">
        <h1>{{ ui.title.value }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="ui.loaded.value"
                      id="character-form" class="uk-form-stacked">
                    <ul v-if="ui.can('edit', 'role')" uk-tab>
                        <li :class="{'uk-active': ui.navigation.page === 'form'}">
                            <a href="" @click.prevent="ui.navigation.setPage('form')">Details</a>
                        </li>
                        <li :class="{'uk-active': ui.navigation.page === 'permissions'}">
                            <a href="" @click.prevent="ui.navigation.setPage('permissions')">Permissions</a>
                        </li>
                    </ul>
                    <player-character-form-navigation v-show="ui.navigation.page === 'form'"
                                                      :character="state.input" :spellcaster="ui.spellcaster"
                                                      :tab="ui.navigation.tab" :errors="state.errors"
                                                      @navigate="ui.navigation.setTab($event)"/>

                    <player-character-form-details-tab v-show="ui.navigation.isFormTabActive('details')"
                                                       :input="state.input"
                                                       :errors="state.errors"
                                                       @update="state.setInfo($event)"
                                                       @next="ui.navigation.setTab('class')"/>
                    <player-character-form-class-tab v-show="ui.navigation.isFormTabActive('class')"
                                                     :input="state.input"
                                                     :errors="state.errors"
                                                     @update="state.setClasses($event)"
                                                     @next="ui.navigation.setTab('background')"/>
                    <player-character-form-background-tab v-show="ui.navigation.isFormTabActive('background')"
                                                          :input="state.input.background_id"
                                                          @update="state.setBackground($event)"
                                                          :errors="state.errors"
                                                          @next="ui.navigation.setTab('proficiency')"/>
                    <player-character-form-proficiency-tab v-show="ui.navigation.isFormTabActive('proficiency')"
                                                           :input="state.input"
                                                           @update="state.setProficiencies($event)"
                                                           :errors="state.errors"
                                                           @next="ui.navigation.setTab('ability')"/>
                    <player-character-form-abilities-tab v-show="ui.navigation.isFormTabActive('ability')"
                                                         :input="state.input"
                                                         @update="state.setAbilityScores($event)"
                                                         :errors="state.errors"
                                                         @next="ui.navigation.setTab('personality')"/>
                    <player-character-form-personality-tab v-show="ui.navigation.isFormTabActive('personality')"
                                                           :input="state.input"
                                                           @update="state.setPersonality($event)"
                                                           :spellcaster="ui.spellcaster"
                                                           @next="ui.navigation.nextOrSave(ui.spellcaster, 'spells')"/>
                    <player-character-form-spell-tab v-if="ui.spellcaster.value && state.input.classes.length > 0"
                                                     v-show="ui.spellcaster.value && ui.navigation.isFormTabActive('spells')"
                                                     :input="state.input"
                                                     :classes="state.input.classes"
                                                     @update="state.setSpells($event)"
                                                     :errors="state.errors"
                                                     @next="state.save"/>

                    <permissions-form v-show="ui.navigation.page === 'permissions' && ui.can('edit', 'role')"
                                      entity="character" :id="id"
                                      v-model="state.input.permissions"/>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useCharacterStore } from '@campaign/stores/characters'
import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, onMounted } from 'vue'
import PermissionsForm from '../../partial/permissions-form'
import PlayerCharacterFormAbilitiesTab from './components/player-character-form-abilities-tab'
import PlayerCharacterFormBackgroundTab from './components/player-character-form-background-tab'
import PlayerCharacterFormClassTab from './components/player-character-form-class-tab'
import PlayerCharacterFormDetailsTab from './components/player-character-form-details-tab'
import PlayerCharacterFormNavigation from './components/player-character-form-navigation'
import PlayerCharacterFormPersonalityTab from './components/player-character-form-personality-tab'
import PlayerCharacterFormProficiencyTab from './components/player-character-form-proficiency-tab'
import PlayerCharacterFormSpellTab from './components/player-character-form-spell-tab'
import { usePlayerCharacterForm } from './player-character-form.state'
import { useFormNavigation, useSpellcaster, useTitle } from './player-character-form.ui'

export default {
    name: 'player-character-form',
    props: ['id'],
    setup(props) {
        const store = useCharacterStore()
        const main = useMainStore()
        const { backgrounds, classes, races } = storeToRefs(store)
        const state = usePlayerCharacterForm(store, props.id)
        const navigation = useFormNavigation(state.save)
        const title = useTitle(props.id, state.input)
        const spellcaster = useSpellcaster(state, classes)

        const loaded = computed(() => classes.value && races.value && backgrounds.value && state.input !== null)

        onMounted(() => state.init())

        return {
            state,
            info: {
                classes,
                races
            },
            ui: {
                can: main.can,
                navigation,
                spellcaster,
                title,
                loaded
            }
        }
    },
    components: {
        PermissionsForm,
        PlayerCharacterFormAbilitiesTab,
        PlayerCharacterFormBackgroundTab,
        PlayerCharacterFormClassTab,
        PlayerCharacterFormDetailsTab,
        PlayerCharacterFormNavigation,
        PlayerCharacterFormPersonalityTab,
        PlayerCharacterFormProficiencyTab,
        PlayerCharacterFormSpellTab
    }
}
</script>