import { useStore } from "vuex";
import { computed, reactive } from "vue";

export const useState = (ctx, ui) => {
    const store = useStore()
    return {
        proficiency: reactive(emptyProficiency),
        proficiencies: store.Races.state.proficiencies,
        proficiencyOptions: computed(() => {
            const proficiencies = {};
            for (const proficiency of state.proficiencies || []) {
                if (!proficiencies.hasOwnProperty(proficiency.type)) {
                    proficiencies[proficiency.type] = []
                }
                proficiencies[proficiency.type].push(proficiency)
            }
            return proficiencies;
        }),
        save() {
            if (state.proficiency.id > 0) {
                ctx.emit('input', this.proficiency)
                ui.close()
            }
        }
        ,
        reset() {
            state.proficiency = emptyProficiency
        }
    }
}

const emptyProficiency = {
    id: 0,
    optional: false
};
