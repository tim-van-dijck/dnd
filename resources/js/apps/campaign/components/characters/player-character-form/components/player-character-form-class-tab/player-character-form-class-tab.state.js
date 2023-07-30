import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const usePlayerCharacterClassState = (props) => {
    const store = useCharacterStore()
    const { classes } = storeToRefs(store)
    const state = reactive({
        input: [],
        setInput(input) {
            this.input = input
        },
        addClass() {
            this.input.push({ class_id: null, subclass_id: null, level: 1 })
        },
        removeClass(index) {
            this.input.splice(index, 1)
        },
        init() {
            this.setInput(props.input?.classes || [])
            if (this.input.length === 0) {
                this.addClass()
            }
        },
        changeChoice(charClass, featureId, index, choice) {
            if (!charClass.hasOwnProperty('features')) {
                charClass.features = {}
            }
            if (choice === '') {
                delete charClass.features[featureId][index]
                if (Object.keys(charClass.features).length === 0) {
                    delete charClass.features[featureId][index]
                }
            } else {
                if (!charClass.features.hasOwnProperty(featureId)) {
                    charClass.features[featureId] = {}
                }
                charClass.features[featureId][index] = parseInt(choice)
            }
        },
        chosen(charClass, featureId, choiceId, index) {
            if (charClass.hasOwnProperty('features') && charClass.features.hasOwnProperty(featureId)) {
                if (charClass.features[featureId][index] == choiceId) {
                    return false
                }
                return Object.values(charClass.features[featureId]).map(item => parseInt(item)).includes(choiceId)
            }
            return false
        }
    })

    const classOptions = computed(() => {
        const options = []
        for (const classId in classes.value) {
            options.push({ id: classId, name: classes.value[classId].name })
        }
        return options
    })
    const subclasses = computed(() => {
        const subclasses = {}
        for (const classId in classes.value) {
            if (!subclasses.hasOwnProperty(classId)) {
                subclasses[classId] = {}
            }
            for (const subclass of classes.value?.[classId]?.subclasses || []) {
                subclasses[classId][subclass.id] = { id: subclass.id, name: subclass.name }
            }
        }
        return subclasses
    })

    return {
        classOptions, state, subclasses
    }
}