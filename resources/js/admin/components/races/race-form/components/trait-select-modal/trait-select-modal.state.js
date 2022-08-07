import { computed, reactive } from 'vue'

export const useState = (traits, ctx, ui) => {
    const state = reactive({
        errors: {},
        trait: reactive({ ...emptyTrait }),
        reset() {
            this.trait = { ...emptyTrait }
        },
        save() {
            if (this.trait.id > 0 || this.trait.name?.length > 0) {
                const trait = { ...this.trait }
                if (this.trait.id > 0) {
                    delete trait.name
                    delete trait.description
                } else {
                    delete trait.id
                }
                ctx.emit('input', trait)
                this.reset()
                ui.close()
            }
        }
    })

    const selectedTrait = computed(() => {
        if (state.trait.id > 0) {
            return traits?.value?.find(trait => trait.id === state.trait.id) || null
        }
        return null
    })
    const traitOptions = computed(() => {
        return traits?.value?.map(({ id, name, description }) => (
            { id, name, description }
        )) || null
    })
    return { state, selectedTrait, traitOptions }
}

const emptyTrait = {
    id: 0,
    name: null,
    description: null
}
