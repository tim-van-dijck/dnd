import { useEffect } from "react";
import { DraggableItem } from "../../../../../components/layout/Draggable/types";
import { useModals } from "../../../../admin/modals";
import { useJournalRepository } from "../../../repositories/JournalRepository";

export const useJournalOverviewState = () => {
  const journalRepository = useJournalRepository()
  const { confirmDelete } = useModals()

  useEffect(() => void journalRepository.load(), [])

  const sort = (items: DraggableItem[]) => journalRepository.sort(items.map(({ key }) => key as number))

  return (
    {
      entries: journalRepository.entries?.data || [],
      loading: journalRepository.entries === null,
      sort,
      destroy: (journal) => {
        confirmDelete(
          'journal entry',
          () => journalRepository.destroy(journal.id).then(() => journalRepository.load())
        )
      }
    }
  )
}