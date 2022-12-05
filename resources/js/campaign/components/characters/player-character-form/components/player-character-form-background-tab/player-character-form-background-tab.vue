<template>
    <div id="background-tab">
        <div v-if="info.backgrounds != null" class="uk-margin-bottom">
            <label for="background-select" class="uk-label"></label>
            <select name="background-select" id="background-select" class="uk-select" v-model="state.selection">
                <option :value="0">None</option>
                <option v-for="background in info.backgrounds.value" :value="background.id">{{
                        background.name
                    }}
                </option>
            </select>
        </div>
        <p v-else class="uk-text-center">
            <i class="fas fa-sync fa-spin fa-2x"></i>
        </p>
        <div v-if="info.selected.value" id="active-background">
            <h2>{{ info.selected.value.name }}</h2>
            <ul>
                <li><b>Skill Proficiencies:</b> {{ info.selected.value.skills }}</li>
                <li v-if="info.selected.value.tools.length > 0"><b>Tool Proficiencies:</b> {{
                        info.selected.value.tools
                    }}
                </li>
                <li v-if="info.selected.value.language_choices > 0"><b>Languages:</b> {{
                        `${info.selected.value.language_choices} of choice`
                    }}
                </li>
            </ul>
            <div v-for="feature in info.selected.value.features">
                <h4>Feature: {{ feature.name }}</h4>
                <div v-html="feature.description"></div>
            </div>
        </div>
        <div class="uk-alert-primary" v-else-if="state.selection == ''" uk-alert>
            <p>You've chosen not to use a background</p>
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
import { useCharacterStore } from '@campaign/stores/characters'
import { onMounted, watch } from 'vue'
import { usePlayerCharacterBackgroundState } from './player-character-form-background.state'

export default {
    name: 'player-character-form-background-tab',
    props: ['value', 'errors'],
    setup(props, ctx) {
        const store = useCharacterStore()
        const { backgrounds, selected, state } = usePlayerCharacterBackgroundState(props)

        onMounted(() => {
            store.loadBackgrounds()
            state.setSelection(props.value || 0)
        })

        watch(() => state.selection, () => ctx.emit('input', state.selection))

        return {
            info: {
                backgrounds,
                selected
            },
            state,
            ui: {
                next: () => ctx.emit('next')
            }
        }
    }
}
</script>