import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { Permission, Role, RoleInput } from "../../../types/roles";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setPermissions, setRoles } from "../stores/roles";
import { RoleRepositoryInterface } from "../types/repositories";

export const useRoleRepository = (): RoleRepositoryInterface => {
  const roles = useCampaignSelector(state => state.roles.roles)
  const permissions = useCampaignSelector(state => state.roles.permissions)
  const dispatch = useCampaignDispatch()
  const url = '/api/campaign/roles'
  const messageBus = useMessageBus()
  const repo = useRepository<Role>(url)

  const page = (number: number): Promise<PaginatedData<Role>> | null => {
    if (roles != null && number > 0 && number <= roles.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setRoles(records))
        return records
      }) || null
    }
    return null
  }

  return {
    roles,
    permissions,
    previous: () => (
      roles?.meta?.current_page > 1
    ) ? page(roles?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        roles?.meta?.current_page < roles?.meta?.last_page
      ) ? page(roles?.meta?.current_page + 1) : null,
    load: (): Promise<PaginatedData<Role>> => repo.load().then((records) => {
      dispatch(setRoles(records));
      return records
    }),
    loadPermissions: (): Promise<Permission[]> => axios.get(`/api/permissions`).then((response) => {
      dispatch(setPermissions(response.data))
      return response.data
    }),
    find: (id: number): Promise<Role> => axios.get(`${url}/${id}`).then((response) => response.data.data),
    store: (role: RoleInput): Promise<void> => axios.post(url, role).then(() => messageBus.success('Role saved!')),
    update: (id: number, role: RoleInput): Promise<void> =>
      axios.put(`${url}/${id}`, role).then(() => messageBus.success('Role saved!')),
    destroy: (id: number): Promise<void> => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Role successfully deleted!')))
  }
}