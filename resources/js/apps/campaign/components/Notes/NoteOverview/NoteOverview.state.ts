import { useEffect } from "react";
import { useModals } from "../../../../admin/modals";
import { useNoteRepository } from "../../../repositories/NoteRepository";

export const useNoteOverviewState = () => {
  const noteRepository = useNoteRepository()
  const { confirmDelete } = useModals()

  useEffect(() => void noteRepository.load(), [])

  return (
    {
      noteRepository,
      destroy: (noteId) => {
        confirmDelete(
          'note',
          () => noteRepository.destroy(noteId).then(() => noteRepository.load())
        )
      }
    }
  )
}