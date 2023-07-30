import { EntityUserPermissionList } from "../permissions";

export interface LocationInput {
  name: string
  type: string
  map: string | File
  description: string
  private: boolean
  location_id?: number
  permissions?: EntityUserPermissionList
}