<template>
    <div id="proficiency-tab">
        <div uk-accordion="multiple: true;">
            <language-selection class="uk-open"
                                :background="ui.background"
                                :race="ui.race"
                                :subrace="ui.subrace"
                                v-model="state.input.languages"/>
            <skill-selection class="uk-accordion-content uk-open"
                             :background="ui.background"
                             :classes="characterClasses"
                             :race="ui.race"
                             :subrace="ui.subrace"
                             v-model="state.input.skills"/>
            <tool-selection class="uk-open"
                            :background="ui.background"
                            :classes="characterClasses"
                            :race="ui.race"
                            :subrace="ui.subrace"
                            v-model="state.input.tools"/>
            <instrument-selection class="uk-open"
                                  :background="ui.background"
                                  :classes="characterClasses"
                                  :race="ui.race"
                                  :subrace="ui.subrace"
                                  v-model="state.input.instruments"/>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="ui.next">Next <i
                class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
import { watch } from 'vue'
import InstrumentSelection from './components/instrument-selection'
import LanguageSelection from './components/language-selection'
import SkillSelection from './components/skill-selection'
import ToolSelection from './components/tool-selection'
import {
    usePlayerCharacterProficiencyState,
    useProficiencyStateUpdates
} from './player-character-form-proficiency-tab.state'

export default {
    name: 'player-character-form-proficiency-tab',
    components: { LanguageSelection, InstrumentSelection, ToolSelection, SkillSelection },
    props: ['backgroundId', 'characterClasses', 'errors', 'info', 'value'],
    setup(props, ctx) {
        const { background, race, state, subrace } = usePlayerCharacterProficiencyState(props)
        useProficiencyStateUpdates(state, props)
        watch(() => state.input, () => ctx.emit('input', state.input))

        return {
            state,
            ui: {
                background,
                next: () => ctx.emit('next'),
                race,
                subrace
            }
        }
    }
}
</script>

<style scoped>

</style>