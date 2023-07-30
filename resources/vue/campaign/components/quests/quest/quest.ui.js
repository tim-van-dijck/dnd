import { computed } from 'vue'

export const useObjectives = (state) => {
    const objectives = computed(() => {
        return state.quest.objectives.filter((item) => {
            return !item.optional
        }) || []
    })
    const optional = computed(() => {
        return state.quest.objectives.filter((item) => {
            return item.optional
        }) || []
    })

    return { objectives, optional }
}