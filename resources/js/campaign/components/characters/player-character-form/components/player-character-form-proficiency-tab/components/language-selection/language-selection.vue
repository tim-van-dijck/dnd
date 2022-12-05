<template>
    <div class="languages">
        <div class="uk-accordion-title"><h2>Languages</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-child-width-1-4@m uk-child-width-1-3@s uk-grid-small uk-grid-match"
                 v-if="languages.known.length + state.selection.length > 0" uk-grid>
                <div v-for="language in languages.known">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ language.name }}</div>
                        <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                    </div>
                </div>
                <div v-for="(language) in selectedLanguages.value">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ language.name }}</div>
                        <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                @click.prevent="state.removeLanguage(language.id)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                    </div>
                </div>
            </div>
            <div class="uk-margin"
                 v-if="optionalLanguages.value > 0 && optionalLanguages.value - state.selection.length > 0">
                <label for="language">Choose {{ optionalLanguages.value - state.selection.length }} languages</label>
                <select name="language" id="language" class="uk-select" @input="state.addLanguage">
                    <option :value="null">- Make a choice -</option>
                    <option v-for="language in languages.value.optional" :value="language.id"
                            :disabled="languages.value.known.includes(language.id)">
                        {{ language.name }}
                    </option>
                </select>
            </div>
            <div class="uk-alert uk-alert-warning"
                 v-if="languages.known.length === 0 && optionalLanguages.value === 0">
                <p>There are no languages available. Have you selected a race yet?</p>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, watch } from 'vue'
import { useLanguageSelectionState } from './language-selection.state'

export default {
    name: 'language-selection',
    props: ['background', 'race', 'subrace', 'value'],
    setup(props, ctx) {
        const { languages, optionalLanguages, selectedLanguages, state } = useLanguageSelectionState(props)
        onMounted(() => state.init())
        watch(() => state.selection, () => ctx.emit(state.selection))

        return { languages, optionalLanguages, selectedLanguages, state }
    }
}
</script>