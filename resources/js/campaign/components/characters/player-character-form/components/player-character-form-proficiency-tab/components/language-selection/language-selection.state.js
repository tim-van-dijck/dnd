import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const useLanguageSelectionState = (props) => {
    const main = useMainStore()
    const { languages: availableLanguages } = storeToRefs(main)

    const state = reactive({
        selection: [],
        addLanguage(event) {
            this.selection.splice(this.selection.length, 1, parseInt(event.target.value))
            event.target.value = ''
        },
        removeLanguage(languageId) {
            this.selection.splice(this.selection.indexOf(languageId), 1)
        },
        init() {
            main.loadLanguages()
                .then(() => {
                    if ((
                        props.value || []
                    ).length > 0) {
                        for (const language of props.value) {
                            const known = languages.known.find((item) => item.id == parseInt(language))
                            if (known == null) {
                                state.selection.push(parseInt(language))
                            }
                        }
                    }
                })
        }
    })
    const languages = computed(() => {
        let languages = {
            known: [],
            optional: []
        }
        if (props.race) {
            for (const language of props.race?.languages || []) {
                if (language.optional) {
                    if (!state.selection.includes(language.id)) {
                        languages.optional.push(language)
                    }
                } else {
                    languages.known.push(language)
                }
            }

            if (props.subrace) {
                for (const language of props.subrace?.languages || []) {
                    if (language.optional) {
                        const alreadyChosen = state.selection.includes(language.id)
                        const alreadyKnown = languages.known.find(item => item.id === language.id) != null
                        if (!alreadyChosen && !alreadyKnown) {
                            languages.optional.push(language)
                        }
                    } else {
                        languages.known.push(language)
                    }
                }
            }
        }

        if (props.background && props.background.language_choices > 0) {
            let backgroundChoices = 0
            for (const selection of state.selection) {
                if (props.race && (
                    props.race.languages || []
                ).find(item => item.id === selection)) {
                    continue
                }
                if (props.subrace && (
                    props.subrace.languages || []
                ).find(item => item.id === selection)) {
                    continue
                }
                backgroundChoices++
            }
            if (backgroundChoices < props.background.language_choices) {
                languages.optional = (
                    availableLanguages || []
                )
                    .filter((language) => {
                        const notKnown = languages.known.find(item => item.id === language.id) == null
                        return !state.selection.includes(language.id) && notKnown
                    })
            }
        }
        return languages
    })
    const optionalLanguages = computed(() => {
        let amount = 0
        if (props.race) {
            amount += props.race.optional_languages
        }
        if (props.subrace) {
            amount += props.subrace.optional_languages
        }
        if (props.background) {
            amount += props.background.language_choices
        }
        return amount
    })
    const selectedLanguages = computed(() => {
        return state.selection?.map((languageId) => availableLanguages?.find(language => language.id
            === languageId))
    })

    return { languages, optionalLanguages, selectedLanguages, state }
}