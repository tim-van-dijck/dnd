import { JournalEntry, JournalEntryInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setEntries } from "../stores/journal";
import { JournalRepositoryInterface } from "../types/repositories";

export const useJournalRepository = (): JournalRepositoryInterface => {
  const entries = useCampaignSelector(state => state.journal.entries)
  const dispatch = useCampaignDispatch()
  const url = '/api/campaign/journal'
  const messageBus = useMessageBus()
  const repo = useRepository<JournalEntry>(url)

  const page = (number: number): Promise<PaginatedData<JournalEntry>> | null => {
    if (entries != null && number > 0 && number <= entries.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setEntries(records))
        return records
      }) || null
    }
    return null
  }

  return {
    entries,
    previous: () => (
      entries?.meta?.current_page > 1
    ) ? page(entries?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        entries?.meta?.current_page < entries?.meta?.last_page
      ) ? page(entries?.meta?.current_page + 1) : null,
    load: () => repo.load().then((records) => {
      dispatch(setEntries(records));
      return records
    }),
    find: (id: number): Promise<JournalEntry> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    store: (entry: JournalEntryInput) => axios.post(url, entry).then(() => messageBus.success('Journal entry saved!')),
    update: (id: number, entry: JournalEntryInput) =>
      axios.put(`${url}/${id}`, entry).then(() => messageBus.success('Journal entry saved!')),
    sort: (ids: number[]) => {
      return axios.post(`${url}/sort`, { list: ids })
        .then(() => {
          dispatch(
            setEntries({
                ...entries,
                data: entries?.data?.map(
                  (entry) => (
                    {
                      ...entry,
                      order: ids.findIndex(id => id === entry.id) + 1
                    }
                  )
                )?.sort((a, b) => a.order - b.order) || null
              }
            )
          )
        })
    },
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Journal entry successfully deleted!')))
  }
}