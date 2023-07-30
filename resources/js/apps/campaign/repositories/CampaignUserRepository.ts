import { User, UserInput } from "@dnd/types";
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

  return {
    users,
    load: () => axios.get(url).then((response: AxiosResponse<PaginatedData<User>>) => {
      const records = response.data.data;
      dispatch(setUsers(records));
      return records
    }),
    find: (id: number): Promise<User> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    invite: (user: UserInput) => axios.post(`${url}/invite`, user).then(() => messageBus.success('User invited!'))
  }
}