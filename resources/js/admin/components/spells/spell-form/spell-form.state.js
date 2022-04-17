import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const router = useRouter()
const store = useStore()

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


export const state = {
    errors: reactive({}),
    schools,
    spell: reactive(emptySpell),
    save() {
        let promise;
        if (this.id > 0) {
            promise = store.dispatch('Spells/update', { id: this.id, spell: this.spell });
        } else {
            promise = store.dispatch('Spells/store', { spell: this.spell })
        }

        promise
            .then(() => {
                router.push({ name: 'spells' });
            })
            .catch((exception) => {
                state.errors = exception.response.data.errors
                store.dispatch('Messages/error', exception.response.data.message, { root: true });
            });
    }
}