import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useMessageStore } from '../../../../stores/messages'

export const useState = (store) => {
    const router = useRouter()

    return reactive({
        errors: {},
        race: emptyRace,
        setRace(race) {
            this.race = { ...race }
        },
        addTrait(trait) {
            this.race.traits = [...this.race.traits, trait]
        },
        removeTrait(index) {
            if (this.race.traits.hasOwnProperty(index)) {
                this.race.traits?.splice(index, 1)
            }
        },
        sizes,
        save() {
            const { id, ...race } = this.race
            race.traits = race.traits.map((trait) => trait.id ? { id: trait.id } : trait)

            const promise = id ? store.update({ id, race }) : store.store(race)
            promise
                .then(() => {
                    router.push({ name: 'races' })
                    this.errors = {}
                })
                .catch((exception) => {
                    this.errors = exception.response.data.errors
                    const messages = useMessageStore()
                    messages.error(exception.response.data.message)
                })
        },
        remove(type, id) {
            const index = this.race?.[type]?.findIndex((item) => item.id === id)
            if (index != null) {
                this.race?.[type]?.splice(index, 1)
            }
        }
    })
}

const emptyRace = {
    name: '',
    description: '',
    size: '',
    speed: 30,
    languages: [],
    optional_languages: 0,
    proficiencies: [],
    optional_proficiencies: 0,
    ability_bonuses: [],
    optional_ability_bonuses: 0,
    optional_feats: 0,
    traits: []
}

const sizes = ['Tiny', 'Small', 'Medium', 'Large', 'Huge', 'Gargantuan']