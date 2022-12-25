import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'
import CharacterFormatter from '../../../lib/CharacterFormatter'

export const usePlayerCharacterForm = (store, id) =>
    reactive({
        input: null,
        errors: {},
        setInput(input) {
            this.input = input
        },
        setInfo(info) {
            this.input.info = { ...info }
        },
        setClasses(classes) {
            this.input.classes = [...classes]
        },
        setBackground(backgroundId) {
            this.input.background_id = backgroundId
        },
        setProficiencies(proficiencies) {
            this.input.proficiencies = { ...proficiencies }
        },
        setErrors(errors) {
            this.errors = errors
        },
        init() {
            store.loadRaces()
            store.loadClasses()
            store.loadBackgrounds()

            if (id) {
                return store.find(id).then((character) => {
                    this.setInput(CharacterFormatter.format(character))
                })
            } else {
                const char = getEmptyCharacter()
                this.setInput(char)
                return Promise.resolve(char)
            }
        },
        save() {
            let promise
            if (id) {
                promise = store.update({ id, character: this.input })
            } else {
                promise = store.store(this.input)
            }
            promise.then((character) => {
                if (Object.keys(this.errors).length === 0) {
                    this.$router.push({ name: 'pc-detail', params: { id: character.id } })
                }
            })
        }
    })

const getEmptyCharacter = () => (
    {
        type: 'player',
        ability_scores: {
            STR: 3,
            DEX: 3,
            CON: 3,
            INT: 3,
            WIS: 3,
            CHA: 3
        },
        info: {
            race_id: null,
            subrace_id: null,
            alignment: null
        },
        classes: [],
        personality: {
            trait: '',
            ideal: '',
            bond: '',
            flaw: ''
        },
        proficiencies: {},
        background_id: null
    }
)

export const useRelated = (input) => {
    const store = useCharacterStore()
    const { backgrounds, races } = storeToRefs(store)

    const background = computed(() => backgrounds.value?.find(item => item.id === input.background_id) || null)
    const race = computed(() => races.value?.find(race => race.id === input.info.race_id) || null)
    const subrace = computed(() => race.value?.subraces?.find(item => item.id === input.info.subrace_id) || null)

    return { background, race, subrace }
}