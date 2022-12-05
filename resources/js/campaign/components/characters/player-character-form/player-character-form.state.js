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
                this.setInput(getEmptyCharacter())
                return Promise.resolve(getEmptyCharacter())
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

export const useRelated = (raceId, subraceId, backgroundId) => {
    const store = useCharacterStore()
    const { backgrounds, races } = storeToRefs(store)

    const background = computed(() => backgrounds.value?.find(item => item.id == backgroundId) || null)
    const race = computed(() => races?.[raceId] || null)
    const subrace = computed(() => race?.subraces?.find(item => item.id == subraceId) || null)

    return { background, race, subrace }
}