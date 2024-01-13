import { Location } from "@dnd/types";
import { useEffect, useState } from "react";
import { useLocationRepository } from "../../../repositories/LocationRepository";

export const useLocation = (id: number) => {
  const locationRepository = useLocationRepository()
  const [ location, setLocation ] = useState<Location | null>(null)

  useEffect(() => {
    locationRepository.find(id).then(setLocation)
  }, [])

  return {
    location
  }
}