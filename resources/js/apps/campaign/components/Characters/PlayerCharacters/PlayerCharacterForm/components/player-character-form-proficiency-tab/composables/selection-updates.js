import { watch } from 'vue'

export const useSelectionUpdates = (props, ctx, state) => {
    watch(props.form.classes, (newClasses) => {
        const remaining = newClasses.map((nc) => typeof nc.class_id === 'number' ? nc.class_id : parseInt(nc.class_id))
            .filter(Boolean)
        const proficiencies = state.selection.filter((item) => {
            const originId = typeof item.origin_id === 'number' ? item.origin_id : parseInt(item.origin_id)
            return item.origin_type !== 'class' ||
                remaining.includes(originId)
        })
        state.setSelection(proficiencies)
    })

    watch(props.form.background_id, () => {
        const proficiencies = state.selection.filter((item) => item.origin_type !== 'background')
        state.setSelection(proficiencies)
    })

    watch(state, () => ctx.emit('update', [...state.selection]))
}