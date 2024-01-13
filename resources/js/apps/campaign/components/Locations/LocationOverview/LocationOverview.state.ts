import { useEffect } from "react";
import { useModals } from "../../../../admin/modals";
import { useLocationRepository } from "../../../repositories/LocationRepository";

export const useLocationOverviewState = () => {
  const { confirmDelete } = useModals()
  const locationRepository = useLocationRepository()

  useEffect(() => void locationRepository.load(), [])

  return (
    {
      locationRepository,
      destroy: (location) => {
        confirmDelete(
          'location',
          () => locationRepository.destroy(location).then(() => locationRepository.load())
        )
      }
    }
  )
}