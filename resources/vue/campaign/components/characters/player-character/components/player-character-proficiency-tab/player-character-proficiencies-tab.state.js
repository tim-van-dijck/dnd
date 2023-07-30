import { computed } from 'vue'

export const useCharacterProficiencies = (languages, proficiencies) => {
    const characterLanguages = computed(() => {
        return languages.value.filter((language) => proficiencies.languages.includes(
            language.id))
    })

    const skills = computed(() => proficiencies.skills?.filter((skill) => true) || [])
    const tools = computed(() => proficiencies.tools?.filter((tool) => true) || [])
    const instruments = computed(() => proficiencies.instruments?.filter((instrument) => true) || [])
    const armor = computed(() => proficiencies.armor?.map(item => item.name) || [])
    const weapons = computed(() => proficiencies.weapons?.map(item => item.name) || [])

    return {
        armor,
        characterLanguages,
        instruments,
        tools,
        skills,
        weapons
    }
}