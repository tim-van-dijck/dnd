import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const router = useRouter();
const store = useStore();

export const useState = (props) => {
    return reactive({
        id: props.id,
        errors: {},
        race: emptyRace,
        sizes,
        save() {
            store.dispatch(`Races/${this.id > 0 ? 'update' : 'store'}`, { id: this.id || null, race: this.race })
                .then(() => {
                    router.push({ name: 'races' });
                    this.errors = {}
                })
                .catch((exception) => {
                    this.errors = exception.response.data.errors;
                    store.dispatch('Messages/error', exception.response.data.message, { root: true });
                });
        },
        remove(type, id) {
            const index = this.race?.[type]?.findIndex((item) => item.id === id)
            if (index != null) {
                this.race?.[type]?.splice(index, 1)
            }
        },
        removeTrait(index) {
            if (this.race.traits.hasOwnProperty(index)) {
                this.race.traits?.splice(index, 1)
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