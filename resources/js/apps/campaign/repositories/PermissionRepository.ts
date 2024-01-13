import { EntityUserPermissionList } from "@dnd/types";
import axios, { AxiosResponse } from "axios";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setPermissions } from "../stores/permissions";
import { PermissionRepositoryInterface } from "../types/repositories";

export const usePermissionRepository = (): PermissionRepositoryInterface => {
  const permissions = useCampaignSelector(state => state.permissions.permissions)
  const dispatch = useCampaignDispatch()

  return {
    permissions,
    fetch: ({ entity, id }) => {
      if (entity && id) {
        if (!permissions?.[entity]?.hasOwnProperty(id)) {
          return axios.get(`/api/campaign/permissions/${entity}/${id}`)
            .then((response: AxiosResponse<EntityUserPermissionList>) => {
              const entityPermissions = { ...response.data }
              const updated = {
                ...permissions,
                [entity]: {
                  ...(
                    permissions?.[entity] || {}
                  ), [id]: entityPermissions
                }
              }
              dispatch(setPermissions(updated))
              return updated[entity][id]
            })
            .catch(() => {
            })
        }
      }
      return Promise.resolve()
    }
  }
}