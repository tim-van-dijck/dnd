import { useEffect } from "react";
import { useModals } from "../../../../../admin/modals";
import { useCharacterRepository } from "../../../../repositories/CharacterRepository";

export const useCharacterOverviewState = () => {
  const { confirmDelete } = useModals()
  const characterRepository = useCharacterRepository()

  useEffect(() => void characterRepository.load({ type: 'player' }), []);

  return (
    {
      characterRepository,
      destroy: (character: number) => {
        confirmDelete(
          'character',
          () => characterRepository.destroy(character).then(() => characterRepository.load())
        )
      }
    }
  )
}