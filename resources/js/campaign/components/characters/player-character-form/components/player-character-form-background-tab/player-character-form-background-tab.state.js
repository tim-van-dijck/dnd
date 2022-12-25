import { useCharacterStore } from '@campaign/stores/characters'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { computed, reactive } from 'vue'

export const usePlayerCharacterBackgroundState = (props) => {
    const store = useCharacterStore()
    const { backgrounds } = storeToRefs(store)
    const state = reactive({
        selection: 0,
        setSelection(selection) {
            this.selection = selection
        }
    })

    const selected = computed(() => {
        const background = backgrounds.value?.find((background) => background.id == state.selection)
        if (background == null) {
            return null
        }
        const selected = { ...background }
        selected.skills = selected.skills.map((item) => item.name).join(', ')

        const tools = selected.tools.map((item) => item.name)
        if (selected.tool_choices > 0) {
            tools.push(`${selected.tool_choices} type(s) of tools`)
        }
        if (selected.instrument_choices > 0) {
            tools.push(`${selected.instrument_choices} type(s) of instruments`)
        }
        selected.tools = tools.join(', ')
        return selected
    })

    return { backgrounds, selected, state }
}