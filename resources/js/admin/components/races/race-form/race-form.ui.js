import { computed } from 'vue'

export const useRaceFormUI = (race, languages, proficiencies, traits) => (
    {
        selected: computed(() => (
            {
                ability_bonuses: race.ability_bonuses || [],
                languages: race.languages?.map((lang) => {
                    const language = languages.find(item => item.id === lang.id)
                    return {
                        ...lang,
                        name: language.name
                    }
                }) || [],
                proficiencies: race.proficiencies?.map((prof) => {
                    const proficiency = proficiencies.find(item => item.id === prof.id)
                    return {
                        ...prof,
                        name: proficiency.name
                    }
                }) || [],
                traits: race.traits.map((trait) => {
                    if (trait.hasOwnProperty('id')) {
                        const selected = traits.find((item) => item.id === trait.id)
                        return selected || trait
                    }
                    return trait
                })
            }
        )),
        title: computed(() => race.id ? `Edit ${race.name || 'race'}` : 'Add race')
    }
)