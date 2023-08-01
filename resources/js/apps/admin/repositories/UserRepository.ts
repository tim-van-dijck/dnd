import { User, UserInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useAdminDispatch, useAdminSelector } from "../store";
import { setUsers } from "../stores/users";
import { UserRepositoryInterface } from "./types";

export const useUserRepository = (): UserRepositoryInterface => {
  const url = '/api/admin/users'
  const messageBus = useMessageBus()
  const repo = useRepository<User>(url)
  const users: PaginatedData<User> | null = useAdminSelector(state => state.users.users) as PaginatedData<User> | null
  const dispatch = useAdminDispatch()


  const page = (number: number): Promise<PaginatedData<User>> | null => {
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
    load: () => repo.load().then((records) => {
      dispatch(setUsers(records));
      return records
    }),
    find: (id: number): Promise<User> => axios.get(`${url}/${id}`).then((response) => response.data.data),

    invite: (user: UserInput) => axios.post(`${url}/invite`, user).then(() => messageBus.success('User invited!')),
    store: (user: UserInput) => axios.post(url, user).then(() => messageBus.success('User saved!')),
    update: (id: number, user: UserInput) =>
      axios.put(`${url}/${id}`, user).then(() => messageBus.success('User saved!')),
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('User successfully deleted!'))),

    resetPassword: (id: number) => axios.post(`${url}/reset-password`, { user_id: id })
      .then(() => messageBus.success('Password reset link sent!'))
  }
}