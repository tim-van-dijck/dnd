import { Quest, QuestInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setQuests } from "../stores/quests";
import { QuestRepositoryInterface } from "../types/repositories";

export const useQuestRepository = (): QuestRepositoryInterface => {
  const quests = useCampaignSelector(state => state.quests.quests)
  const dispatch = useCampaignDispatch()
  const url = '/api/campaign/quests'
  const messageBus = useMessageBus()
  const repo = useRepository<Quest>(url)

  const page = (number: number): Promise<PaginatedData<Quest>> | null => {
    if (quests != null && number > 0 && number <= quests.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setQuests(records))
        return records
      }) || null
    }
    return null
  }

  return {
    quests,
    previous: () => (
      quests?.meta?.current_page > 1
    ) ? page(quests?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        quests?.meta?.current_page < quests?.meta?.last_page
      ) ? page(quests?.meta?.current_page + 1) : null,
    load: (): Promise<PaginatedData<Quest>> => repo.load().then((records) => {
      dispatch(setQuests(records));
      return records
    }),
    find: (id: number): Promise<Quest> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    store: (quest: QuestInput): Promise<void> => axios.post(url, quest).then(() => messageBus.success('Quest saved!')),
    update: (id: number, quest: QuestInput): Promise<void> =>
      axios.put(`${url}/${id}`, quest).then(() => messageBus.success('Quest saved!')),
    destroy: (id: number): Promise<void> => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Quest successfully deleted!'))),
    toggleObjective: (questId: number, objectiveId: number, status): Promise<void> => {
      return axios.post(`${url}/${questId}/objectives/${objectiveId}/toggle`, { status }).then(() => {
      })
    }
  }
}