import { computed, reactive } from "vue";
import { useStore } from "vuex";

export const useState = (ctx, ui) => {
    const store = useStore()
    const state = {
        errors: reactive({}),
        trait: reactive(emptyTrait),
        traits: store.Races.state.traits,
        reset() {
            this.trait = emptyTrait
        },
        save() {
            if (state.trait.id > 0 || state.trait.name?.length > 0) {
                const trait = { ...state.trait }
                if (state.trait.id > 0) {
                    delete trait.name
                    delete trait.description
                } else {
                    delete trait.id
                }
                ctx.emit('input', trait)
                ui.close()
            }
        },
        selectedTrait: computed(() => {
            if (state.trait.id > 0) {
                return state.traits?.find(trait => trait.id === state.trait.id) || null
            }
            return null
        }),
        traitOptions: computed(() => {
            return state.traits?.map(({ id, name, description }) => (
                { id, name, description }
            )) || null
        })
    }
    return state
}

const emptyTrait = {
    id: 0,
    name: null,
    description: null
};
