import { useEffect } from "react";
import { useModals } from "../../../../admin/modals";
import { useQuestRepository } from "../../../repositories/QuestRepository";

export const useQuestOverviewState = () => {
  const questRepository = useQuestRepository()
  const { confirmDelete } = useModals()

  useEffect(() => void questRepository.load(), [])

  return (
    {
      questRepository,
      destroy: (quest) => {
        confirmDelete(
          'quest',
          () => questRepository.destroy(quest).then(() => questRepository.load())
        )
      }
    }
  )
}