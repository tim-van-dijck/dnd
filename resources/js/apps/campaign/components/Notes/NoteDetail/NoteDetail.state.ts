import { Note } from "@dnd/types";
import { useEffect, useState } from "react";
import { useNoteRepository } from "../../../repositories/NoteRepository";

export const useNoteDetailState = (id: number) => {
  const noteRepository = useNoteRepository()
  const [ note, setNote ] = useState<Note | null>(null)

  useEffect(() => void noteRepository.find(id).then(setNote), [ id ])

  return {
    note
  }
}