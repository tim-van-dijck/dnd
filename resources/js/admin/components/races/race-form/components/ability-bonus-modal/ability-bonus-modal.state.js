import { reactive } from "vue";

export const useState = (ctx, ui) => {
    return {
        abilities,
        ability: reactive(emptyAbility),
        reset() {
            state.ability = emptyAbility
        },
        save() {
            if (state.ability.id.length === 3) {
                ctx.emit('input', { ...state.ability })
                ui.close()
            }
        },
    }
}

const abilities = [
    { id: 'STR', name: 'Strength' },
    { id: 'DEX', name: 'Dexterity' },
    { id: 'CON', name: 'Constitution' },
    { id: 'INT', name: 'Intelligence' },
    { id: 'WIS', name: 'Wisdom' },
    { id: 'CHA', name: 'Charisma' }
]

const emptyAbility = {
    id: '',
    bonus: 1,
    optional: false
};
