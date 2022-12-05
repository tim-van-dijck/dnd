import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { reactive, watch } from 'vue'
import { useRelated } from '../../player-character-form.state'

export const usePlayerCharacterProficiencyState = (props) => {
    const store = useCharacterStore()
    const { backgrounds, classes } = storeToRefs(store)
    const state = reactive({
        input: {
            languages: [],
            instruments: [],
            skills: [],
            tools: []
        },
        init() {
            if (Object.keys(props.value || {}).length > 0) {
                const choices = {
                    languages: props.value.languages || [],
                    instruments: [],
                    skills: [],
                    tools: []
                }
                for (const type in this.value) {
                    if (['instruments', 'skills', 'tools'].includes(type)) {
                        for (const proficiency of this.value[type]) {
                            choices[type].push(this.formatProficiency(proficiency))
                        }
                    }
                }
                this.input = choices
            }
        },
        setLanguages(languages) {
            this.input.languages = languages
        },
        formatProficiency(proficiency) {
            return { ...proficiency, origin: getProficiencyOrigin() }
        }
    })

    const { background, race, subrace } = useRelated(props.info?.race_id, props.info?.subrace_id, props.backgroundId)

    const getProficiencyOrigin = (proficiency) => {
        switch (proficiency.origin_type) {
            case 'background':
                return backgrounds.find(item => item.id == proficiency.origin_id)?.name || 'Background'
            case 'class':
                return classes[proficiency.origin_id]?.name || 'Class'
            case 'subclass':
                const selectedClass = classes
                    .find((item) => item.subclasses.find(subclass => subclass.id == proficiency.origin_id) != null)
                return selectedClass?.subclasses()
                    ?.find(subclass => subclass.id == proficiency.origin_id)?.name || 'Subclass'
            case 'race':
                return race?.id === proficiency.origin_id ? race?.name || 'Race' : 'Race'
            case 'subrace':
                return subrace?.id === proficiency.origin_id ? subrace?.name || 'Subrace' : 'Subrace'
        }
    }

    return {
        background, race, state, subrace
    }
}

export const useProficiencyStateUpdates = (state, props) => {
    watch(() => props.info.race_id, (value, oldValue) => {
        if (oldValue !== value) {
            state.setLanguages([])
        }
    })
    watch(() => props.info.subrace_id, (value, oldValue) => {
        if (oldValue !== value) {
            state.setLanguages([])
        }
    })
    watch(() => props.info.background_id, (value, oldValue) => {
        if (oldValue !== value) {
            state.setLanguages([])
        }
    })
}