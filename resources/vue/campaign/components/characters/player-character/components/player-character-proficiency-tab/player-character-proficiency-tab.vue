<template>
    <div id="proficiency-tab">
        <div class="languages uk-margin">
            <h2>Languages</h2>
            <div v-if="languages"
                 class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-grid-small uk-grid-match"
                 uk-grid>
                <div v-for="language in characterLanguages">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ language.name }}</div>
                        <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                    </div>
                </div>
            </div>
            <p v-else class="uk-text-center"><i class="fas fa-sync fa-spin fa-2x"></i></p>
        </div>
        <div class="skills uk-margin">
            <h2>Skills</h2>
            <div class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match"
                 uk-grid>
                <div v-for="skill in skills">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ skill.name }}</div>
                        <p><em>({{ skill.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tools uk-margin">
            <h2>Tools</h2>
            <div v-if="tools.length > 0"
                 class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match"
                 uk-grid>
                <div v-for="tool in tools">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ tool.name }}</div>
                        <p><em>({{ tool.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
            <div v-else class="uk-alert-primary" uk-alert>
                This character doesn't have Tool proficiencies
            </div>
        </div>
        <div class="instruments uk-margin">
            <h2>Instruments</h2>
            <div v-if="instruments.length > 0"
                 class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match"
                 uk-grid>
                <div v-for="instrument in instruments">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ instrument.name }}</div>
                        <p><em>({{ instrument.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
            <div v-else class="uk-alert-primary" uk-alert>
                This character doesn't have Instrument proficiencies
            </div>
        </div>
        <div class="weapons-armor uk-margin">
            <h2>Weapons & armor</h2>
            <ul class="uk-list">
                <li v-if="armor.length > 0">
                    {{ armor.join(', ') }}
                </li>
                <li v-if="weapons.length === 0">No weapon proficiencies</li>
                <li v-else>{{ weapons.join(', ') }}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import { useCharacterProficiencies } from './player-character-proficiencies-tab.state'

export default {
    name: 'player-character-proficiency-tab',
    props: ['character', 'proficiencies'],
    setup(props) {
        const main = useMainStore()
        const { languages } = storeToRefs(main)

        onMounted(() => main.loadLanguages())

        const { armor, characterLanguages, instruments, skills, tools, weapons } = useCharacterProficiencies(
            languages,
            props.proficiencies
        )

        return { armor, characterLanguages, instruments, languages, skills, tools, weapons }
    }
}
</script>