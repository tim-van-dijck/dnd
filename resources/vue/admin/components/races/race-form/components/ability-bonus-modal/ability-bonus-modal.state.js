import { reactive } from 'vue'

export const useState = (ctx, ui) => {
    return reactive({
        abilities,
        ability: reactive({ ...emptyAbility }),
        reset() {
            this.ability = { ...emptyAbility }
        },
        save() {
            if (this.ability.ability.length === 3) {
                ctx.emit('input', { ...this.ability })
                this.reset()
                ui.close()
            }
        }
    })
}

const abilities = [
    { ability: 'STR', name: 'Strength' },
    { ability: 'DEX', name: 'Dexterity' },
    { ability: 'CON', name: 'Constitution' },
    { ability: 'INT', name: 'Intelligence' },
    { ability: 'WIS', name: 'Wisdom' },
    { ability: 'CHA', name: 'Charisma' }
]

const emptyAbility = {
    ability: '',
    bonus: 1,
    optional: false
}
