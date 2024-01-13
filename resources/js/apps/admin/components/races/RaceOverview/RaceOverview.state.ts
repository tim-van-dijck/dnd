import { useModals } from '../../../modals'
import { useEffect } from "react";
import { useAdminRepositories } from "../../../providers/AdminRepositoryProvider";

export const useRaceOverviewState = () => {
  const { RaceRepository } = useAdminRepositories()
  const { confirmDelete } = useModals()

  useEffect(() => void RaceRepository.load(), [])

  return (
    {
      destroy: (raceId: number) => {
        confirmDelete(
          'race',
          () => RaceRepository.destroy(raceId).then(() => RaceRepository.load())
        )
      },
      RaceRepository
    }
  )
}