import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { reactive } from 'vue'
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
            if (Object.keys(props.input.proficiencies || {}).length > 0) {
                const choices = {
                    languages: props.input.proficiencies?.languages || [],
                    instruments: [],
                    skills: [],
                    tools: []
                }
                for (const type in props.input.proficiencies) {
                    if (['instruments', 'skills', 'tools'].includes(type)) {
                        for (const proficiency of props.input.proficiencies[type]) {
                            choices[type].push(this.formatProficiency(proficiency))
                        }
                    }
                }
                this.input = choices
            }
        },
        setLanguages(languages) {
            this.input.languages = [...languages]
        },
        setProficiencies(proficiencies, type) {
            if (![
                'skills',
                'tools',
                'instruments'
            ].includes(type)) {
                throw new Error(`Invalid Proficiency type [${type}]`)
            }
            this.input[type] = [...proficiencies]
        },
        formatProficiency(proficiency) {
            return { ...proficiency, origin: getProficiencyOrigin() }
        }
    })

    const { background, race, subrace } = useRelated(props.input)

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