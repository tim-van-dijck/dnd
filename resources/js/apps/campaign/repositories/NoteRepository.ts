import { Note, NoteInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setNotes } from "../stores/notes";
import { NoteRepositoryInterface } from "../types/repositories";

export const useNoteRepository = (): NoteRepositoryInterface => {
  const notes = useCampaignSelector(state => state.notes.notes)
  const dispatch = useCampaignDispatch()
  const url = '/api/campaign/notes'
  const messageBus = useMessageBus()
  const repo = useRepository<Note>(url)

  const page = (number: number): Promise<PaginatedData<Note>> | null => {
    if (notes != null && number > 0 && number <= notes.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setNotes(records))
        return records
      }) || null
    }
    return null
  }

  return {
    notes,
    previous: () => (
      notes?.meta?.current_page > 1
    ) ? page(notes?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        notes?.meta?.current_page < notes?.meta?.last_page
      ) ? page(notes?.meta?.current_page + 1) : null,
    load: () => repo.load().then((records) => {
      dispatch(setNotes(records));
      return records
    }),
    find: (id: number): Promise<Note> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    store: (note: NoteInput) => axios.post(url, note).then(() => messageBus.success('Note saved!')),
    update: (id: number, note: NoteInput) =>
      axios.put(`${url}/${id}`, note).then(() => messageBus.success('Note saved!')),
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Note successfully deleted!')))
  }
}