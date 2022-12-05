import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const usePlayerCharacterDetailState = (store, users, props) => {
    const main = useMainStore()
    const { user } = storeToRefs(main)
    const { races } = storeToRefs(store)

    const state = reactive({
        input: {
            race_id: null,
            subrace_id: null,
            owner_id: null
        },
        setInput(input) {
            this.input = input
        },
        init() {
            this.setInput(props.value)
            if (isOwner.value) {
                users.load()
                    .then(() => {
                        if (this.input?.owner_id === null) {
                            this.input.owner_id = user.id
                        }
                    })
            }
        }
    })

    const subraces = computed(() => {
        if (races) {
            const race = races.value[state.input.race_id]
            if (race) {
                return race.subraces || []
            }
        }
        return []
    })
    const isOwner = computed(() => {
        if (!user.value) {
            return false
        }
        return user.id === state.input?.owner_id || main.hasRole('Admin')
    })

    return { isOwner, state, subraces }
}

export const alignments = [
    { value: 'LG', name: 'Lawful Good' },
    { value: 'NG', name: 'Neutral Good' },
    { value: 'CG', name: 'Chaotic Good' },
    { value: 'LN', name: 'Lawful Neutral' },
    { value: 'TN', name: 'True Neutral' },
    { value: 'CN', name: 'Chaotic Neutral' },
    { value: 'LE', name: 'Lawful Evil' },
    { value: 'NE', name: 'Neutral Evil' },
    { value: 'CE', name: 'Chaotic Evil' }
]