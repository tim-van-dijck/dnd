import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useMessageStore } from '../../../../stores/messages'

const router = useRouter()

const emptySpell = {
    name: '',
    range: '',
    components: [],
    materials: '',
    ritual: false,
    concentration: false,
    duration: '',
    casting_time: '',
    level: '',
    school: '',
    description: '',
    higher_levels: ''
}

const schools = [
    'Abjuration',
    'Conjuration',
    'Divination',
    'Enchantment',
    'Evocation',
    'Illusion',
    'Necromancy',
    'Transmutation'
]

export const useState = (store) => {
    const router = useRouter()

    return {
        state: reactive({
            errors: {},
            schools,
            spell: emptySpell,
            setSpell(spell) {
                this.spell = spell
            },
            save() {
                const { id, ...spell } = this.spell
                const promise = id ? store.update({ id, spell }) : store.store(spell)
                promise
                    .then(() => {
                        router.push({ name: 'spells' })
                        this.errors = {}
                    })
                    .catch((exception) => {
                        this.errors = exception.response.data.errors
                        const messages = useMessageStore()
                        messages.error(exception.response.data.message)
                    })
            }
        })
    }
}
