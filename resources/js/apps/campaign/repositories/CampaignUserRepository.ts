import { CampaignUser, UserInput } from "@dnd/types";
import axios, { AxiosResponse } from "axios";
import { PaginatedData } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setUsers } from "../stores/users";
import { CampaignUserRepositoryInterface } from "../types/repositories";

const url = `/api/campaign/users`

export const useCampaignUserRepository = (): CampaignUserRepositoryInterface => {
  const messageBus = useMessageBus()
  const users = useCampaignSelector(state => state.users.users)
  const dispatch = useCampaignDispatch()

  const load = (): Promise<CampaignUser[]> => axios.get(url)
    .then((response: AxiosResponse<PaginatedData<CampaignUser>>) => {
      const records = response.data.data;
      dispatch(setUsers(records));
      return records
    })

  return {
    users,
    load,
    find: (id: number): Promise<CampaignUser> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    invite: (user: UserInput) => axios.post(`${url}/invite`, user).then(() => messageBus.success('User invited!')),
    update: (id: number, user: UserInput) => axios.post(`${url}/${id}`, user)
      .then(() => messageBus.success('User saved!')),
    destroy: (id: number): Promise<void> => axios.delete(`${url}/${id}`)
      .then(() => load().then(() => messageBus.success('User successfully deleted!')))
  }
}