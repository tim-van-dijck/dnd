import { CampaignUser, CampaignUserInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setUsers } from "../stores/users";
import { CampaignUserRepositoryInterface } from "../types/repositories";

export const useUserRepository = (): CampaignUserRepositoryInterface => {
  const users = useCampaignSelector(state => state.users.users)
  const dispatch = useCampaignDispatch()
  const url = '/api/campaign/users'
  const messageBus = useMessageBus()
  const repo = useRepository<CampaignUser>(url)

  const page = (number: number): Promise<PaginatedData<CampaignUser>> | null => {
    if (users != null && number > 0 && number <= users.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setUsers(records))
        return records
      }) || null
    }
    return null
  }

  return {
    users,
    previous: () => (
      users?.meta?.current_page > 1
    ) ? page(users?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        users?.meta?.current_page < users?.meta?.last_page
      ) ? page(users?.meta?.current_page + 1) : null,
    load: (): Promise<PaginatedData<CampaignUser>> => repo.load().then((records) => {
      dispatch(setUsers(records));
      return records
    }),
    find: (id: number): Promise<CampaignUser> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    invite: (user: CampaignUserInput): Promise<void> => axios.post(`${url}/invite`, user)
      .then(() => messageBus.success('User saved!')),
    update: (id: number, user: CampaignUserInput): Promise<void> =>
      axios.put(`${url}/${id}`, user).then(() => messageBus.success('User saved!')),
    destroy: (id: number): Promise<void> => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('User successfully deleted!')))
  }
}