<template>
    <div id="proficiency-tab">
        <div uk-accordion="multiple: true;">
            <language-selection class="uk-open"
                                :form="input"
                                :input="state.input"
                                @update="state.setLanguages($event)"/>
            <skill-selection class="uk-accordion-content uk-open"
                             :form="input"
                             :input="state.input"
                             @update="state.setProficiencies($event, 'skills')"/>
            <tool-selection class="uk-open"
                            :form="input"
                            :input="state.input"
                            @update="state.setProficiencies($event, 'tools')"/>
            <instrument-selection class="uk-open"
                                  :form="input"
                                  :input="state.input"
                                  @update="state.setProficiencies($event, 'instruments')"/>
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
import { usePlayerCharacterProficiencyState } from './player-character-form-proficiency-tab.state'

export default {
    name: 'player-character-form-proficiency-tab',
    components: { LanguageSelection, InstrumentSelection, ToolSelection, SkillSelection },
    props: ['errors', 'input'],
    setup(props, ctx) {
        const { background, race, state, subrace } = usePlayerCharacterProficiencyState(props)

        watch(state, () => ctx.emit('update', state.input))

        return {
            characterClasses: props.input.classes,
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