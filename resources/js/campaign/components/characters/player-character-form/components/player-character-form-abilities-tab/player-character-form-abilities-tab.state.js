import { computed, reactive, watch } from 'vue'
import { useRelated } from '../../player-character-form.state'

export const usePlayerCharacterAbilitiesState = (props, ctx) => {
    const { race, subrace } = useRelated(props.input)

    const state = reactive({
        input: {
            STR: 3,
            DEX: 3,
            CON: 3,
            INT: 3,
            WIS: 3,
            CHA: 3
        },
        choices: {
            race: [],
            subrace: []
        },
        init() {
            if (props.input.ability_scores) {
                const input = {}
                for (const ability of abilities) {
                    input[ability] = (props.input.ability_scores?.[ability] || 3) - (bonuses.value?.[ability] || 0)
                }
                this.setInput(input)
            }
        },
        addAbilityBonus(value) {
            if (value !== '') {
                const [ability, score] = value.split('_')
                this.choices.race.push({ ability, score })
            }
        },
        setInput(input) {
            this.input = input
        }
    })

    const bonuses = computed(() => {
        const bonuses = {
            STR: 0,
            DEX: 0,
            CON: 0,
            INT: 0,
            WIS: 0,
            CHA: 0
        }
        if (race) {
            for (const ability of race.value?.abilities || []) {
                if (ability.optional) {
                    const chosen = state.choices.race.find(item => item.ability === ability.ability)
                    if (chosen) {
                        bonuses[ability.ability] += parseInt(chosen.score)
                    }
                } else {
                    bonuses[ability.ability] += ability.bonus
                }
            }
            if (subrace) {
                for (const ability of subrace.value?.abilities || []) {
                    if (ability.optional) {
                        const chosen = state.choices.subrace.find(item => item.ability === ability.ability)
                        if (chosen) {
                            bonuses[ability.ability] += parseInt(chosen.score)
                        }
                    } else {
                        bonuses[ability.ability] += ability.bonus
                    }
                }
            }
        }
        return bonuses
    })

    const totalAbilities = computed(() => {
        return abilities.map((ability) => {
            const total = parseInt(state.input[ability]) + parseInt(bonuses.value[ability])
            return {
                score: state.input[ability],
                total,
                name: ability,
                bonus: Math.floor((total - 10) / 2)
            }
        })
    })

    watch(() => totalAbilities.value, () => {
        const scores = {}
        for (const ability of totalAbilities.value) {
            scores[ability.name] = ability.total
        }
        ctx.emit('input', scores)
    })

    return { state, computed: { bonuses, race, subrace, totalAbilities } }
}

const abilities = ['STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA']