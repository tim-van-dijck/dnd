import { reactive } from "vue";
import { useStore } from "vuex";

export const useState = (ctx, ui) => {
    const store = useStore()
    return {
        languages: store.languages,
        language: reactive(emptyLanguage),
        reset() {
            state.language = emptyLanguage
        },
        save() {
            if (state.language.id > 0) {
                ctx.emit('input', { ...state.language })
                ui.close()
            }
        },
    }
}

const emptyLanguage = {
    id: 0,
    optional: false
}