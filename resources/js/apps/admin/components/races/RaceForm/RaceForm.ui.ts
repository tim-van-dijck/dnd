import { RaceInput } from "../../../../../types";
import { useEffect } from "react";
import { useAdminRepositories } from "../../../providers/AdminRepositoryProvider";

export const useRaceFormUI = (race: RaceInput | null, id?: string) => {
  const { RaceRepository } = useAdminRepositories()
  const title = id ? `Edit ${race ? race.name : 'race'}` : 'Add race'

  useEffect(() => {
    void RaceRepository.loadLanguages()
    void RaceRepository.loadProficiencies()
    void RaceRepository.loadTraits()
  }, [])

  const selected = {
    ability_bonuses: race?.ability_bonuses || [],
    languages: race?.languages?.map((lang) => {
      const language = RaceRepository.languages?.find(item => item.id === lang.id)
      return {
        ...lang,
        name: language?.name || ''
      }
    }) || [],
    proficiencies: race?.proficiencies?.map((prof) => {
      const proficiency = RaceRepository.proficiencies?.find((item) => {
        return item.id === prof.id;
      })
      return {
        ...prof,
        name: proficiency?.name || ''
      }
    }) || [],
    traits: race?.traits?.map((trait) => {
      if (trait.hasOwnProperty('id')) {
        const selected = RaceRepository.traits?.find((item) => item.id === trait.id)
        return selected || trait
      }
      return trait
    }) || []
  }

  return { selected, title }
}