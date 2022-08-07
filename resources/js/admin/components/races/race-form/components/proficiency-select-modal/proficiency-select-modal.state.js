import { reactive } from 'vue'

export const useState = (proficiencies, ctx, ui) => {
    return reactive({
        proficiency: reactive({ ...emptyProficiency }),
        reset() {
            this.proficiency = { ...emptyProficiency }
        },
        save() {
            if (this.proficiency.id > 0) {
                ctx.emit('input', { ...this.proficiency })
                this.reset()
                ui.close()
            }
        }
    })
}

const emptyProficiency = {
    id: 0,
    optional: false
}
