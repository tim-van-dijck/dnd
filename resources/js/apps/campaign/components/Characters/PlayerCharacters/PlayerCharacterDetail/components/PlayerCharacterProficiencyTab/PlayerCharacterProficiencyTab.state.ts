import { useLanguages } from '../../../../../../../../hooks/useLanguages'

export const useCharacterProficiencies = (proficiencies) => {
  const { languages } = useLanguages()
  const characterLanguages = languages?.filter((language) => proficiencies.languages.includes(language.id)) || []

  const skills = proficiencies.skills?.filter((skill) => true) || []
  const tools = proficiencies.tools?.filter((tool) => true) || []
  const instruments = proficiencies.instruments?.filter((instrument) => true) || []

  const armor = proficiencies.armor?.map(item => item.name) || []
  const weapons = proficiencies.weapons?.map(item => item.name) || []

  return {
    armor,
    characterLanguages,
    instruments,
    loading: languages === null,
    tools,
    skills,
    weapons
  }
}