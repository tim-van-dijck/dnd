import { useModals } from '../../../modals'
import { useEffect } from "react";
import { useAdminRepositories } from "../../../providers/AdminRepositoryProvider";

export const useSpellOverviewState = () => {
  const { SpellRepository } = useAdminRepositories()
  const { confirmDelete } = useModals()

  useEffect(() => void SpellRepository.load(), [])

  return (
    {
      SpellRepository,
      destroy: (spell) => {
        confirmDelete(
          'spell',
          () => SpellRepository.destroy(spell).then(() => SpellRepository.load())
        )
      }
    }
  )
}
