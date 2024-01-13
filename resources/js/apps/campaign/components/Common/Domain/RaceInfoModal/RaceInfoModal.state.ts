import { useEffect } from 'react'
import { useCharacterRepository } from '../../../../repositories/CharacterRepository'

export const useRaceInfoModalRaces = () => {
  const repo = useCharacterRepository()

  useEffect(() => {
    repo.loadRaces()
  }, [])

  return repo.races || []
}