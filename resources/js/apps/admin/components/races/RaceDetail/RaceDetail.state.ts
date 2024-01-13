import { useEffect, useState } from "react";
import { Race } from "../../../../../types";
import { useAdminRepositories } from "../../../providers/AdminRepositoryProvider";

export const useRaceDetailState = (id?: number) => {
  const { RaceRepository } = useAdminRepositories()
  const [ race, setRace ] = useState<Race | null>(null)

  useEffect(() => {
    if (id) RaceRepository.find(id).then(setRace)
  }, [])

  return { race }
}