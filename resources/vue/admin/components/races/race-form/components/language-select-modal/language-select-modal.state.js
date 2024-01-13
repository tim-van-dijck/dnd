import { reactive } from 'vue'

export const useState = (store, ctx, ui) => {
    return reactive({
        language: reactive({ ...emptyLanguage }),
        reset() {
            this.language = { ...emptyLanguage }
        },
        save() {
            if (this.language.id > 0) {
                ctx.emit('input', { ...this.language })
                this.reset()
                ui.close()
            }
        }
    })
}

const emptyLanguage = {
    id: 0,
    optional: false
}