import { computed, reactive } from 'vue'

export const useRaceInfoModalState = (races) => reactive({
    active: {
        race: null,
        subrace: null,
        tab: 'description',
        trait: null,
        subTrait: null
    },
    setActive(race) {
        this.active.race = race.id
        this.active.tab = 'description'
        if (race.traits.length > 0) {
            this.active.trait = race.traits[0].id
        }
        if (race.subraces.length > 0) {
            const subrace = race.subraces[0]
            this.active.subrace = subrace.id
            this.active.subTrait = subrace.traits[0].id
        } else {
            this.active.subrace = null
            this.active.subTrait = null
        }
    },
    setActiveSubrace(subraceId) {
        const race = races?.find?.((race) => race.id === this.active.race)
        const subrace = race.subraces.find(item => item.id === subraceId)
        this.active.subrace = subraceId
        this.active.subTrait = subrace.traits[0].id
    }
})

export const useRaceInfoModalComputed = (races, active) => {
    const activeRace = computed(() => {
        const race = races?.find?.(active.race)
        return race ? format(race) : null
    })
    const activeSubrace = computed(() => {
        if (activeRace.value && active.subrace) {
            const subrace = activeRace.value.subraces.find(item => item.id === active.subrace)
            return subrace ? format(subrace) : null
        }
        return null
    })

    const activeTrait = computed(() => {
        if (activeRace.value && active.trait) {
            const trait = activeRace.value.traits.find(item => item.id === active.trait)
            return trait || null
        }
        return null
    })
    const activeSubraceTrait = computed(() => {
        if (activeSubrace.value && active.subTrait) {
            const trait = activeSubrace.value.traits.find(item => item.id === active.subTrait)
            return trait || null
        }
        return null
    })

    return { activeRace, activeSubrace, activeTrait, activeSubraceTrait, races }
}

const format = (entity) => ({
    ...entity,
    ability_scores: entity.abilities
        .map((item) => `${item.ability} +${item.bonus}`)
        .join(', '),
    proficiencies: {
        armor: entity.proficiencies.filter(item => item.type === 'Armor' && !item.optional)
            .map(item => item.name),
        skills: entity.proficiencies.filter(item => item.type === 'Skills' && !item.optional)
            .map(item => item.name),
        tools: entity.proficiencies.filter(item => item.type === 'Tools' && !item.optional)
            .map(item => item.name),
        weapons: entity.proficiencies.filter(item => item.type === 'Weapons' && !item.optional)
            .map(item => item.name),
        optional: entity.proficiencies.filter(item => item.optional).map(item => item.name)
    }
})