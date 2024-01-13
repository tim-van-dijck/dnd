import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive, watch } from 'vue'
import { useRelated } from '../../../../PlayerCharacterForm.state'

export const useLanguageSelectionState = (props) => {
  const main = useMainStore()
  const { languages: availableLanguages } = storeToRefs(main)
  const { background, race, subrace } = useRelated(props.form.value)

  const state = reactive({
    selection: [],
    setSelection(selection) {
      this.selection = selection
    },
    refreshFromInput() {
      if ((props.input.languages || []).length > 0) {
        const selection = []
        for (const language of props.input.languages) {
          const known = languages.known.find((item) => item.id === parseInt(language))
          if (known == null) {
            selection.push(parseInt(language))
          }
        }
        state.setSelection(selection)
      }
    },
    addLanguage(event) {
      this.selection.splice(this.selection.length, 1, parseInt(event.target.value))
      event.target.value = ''
    },
    removeLanguage(languageId) {
      this.selection.splice(this.selection.indexOf(languageId), 1)
    },
    init() {
      main.loadLanguages().then(() => this.refreshFromInput())
    }
  })
  const languages = computed(() => {
    const languages = {
      known: [],
      optional: []
    }
    if (race.value) {
      for (const language of race.value?.languages || []) {
        if (language.optional && !state.selection.includes(language.id)) {
          languages.optional.push(language)
        }
        if (!language.optional) {
          languages.known.push(language)
        }
      }

      if (subrace.value) {
        for (const language of subrace.value?.languages || []) {
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

    if (background.value?.language_choices > 0) {
      let backgroundChoices = 0
      for (const selection of state.selection) {
        if (race.value?.languages?.find(item => item.id === selection)) {
          continue
        }
        if (subrace.value?.languages?.find(item => item.id === selection)) {
          continue
        }
        backgroundChoices++
      }
      if (backgroundChoices < background.value?.language_choices) {
        languages.optional = availableLanguages.value
          ?.filter((language) => {
            const notKnown = languages.known.find(item => item.id === language.id) == null
            return !state.selection.includes(language.id) && notKnown
          }) || []
      }
    }
    return languages
  })
  const optionalLanguages = computed(() => {
    let amount = 0
    if (race.value) {
      amount += race.value.optional_languages || 0
    }
    if (subrace.value) {
      amount += subrace.value.optional_languages || 0
    }
    if (background.value) {
      amount += background.value.language_choices || 0
    }
    return amount
  })
  const selectedLanguages = computed(() =>
    state.selection
      ?.map((languageId) => availableLanguages.value?.find(language => language.id === languageId)))
  const remainingChoices = computed(() => optionalLanguages.value - state.selection.length)

  return { languages, optionalLanguages, remainingChoices, selectedLanguages, state }
}


export const useLanguageSelectionUpdates = (state, props) => {
  watch(() => props.form.info.race_id, (value, oldValue) => {
    if (oldValue !== value) {
      state.setSelection([])
    }
  })
  watch(() => props.form.info.subrace_id, (value, oldValue) => {
    if (oldValue !== value) {
      state.setSelection([])
    }
  })
  watch(() => props.form.background_id, (value, oldValue) => {
    if (oldValue !== value) {
      state.setSelection([])
    }
  })

  watch(props.form.proficiencies.languages, () => state.refreshFromInput())
}