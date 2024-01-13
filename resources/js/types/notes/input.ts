import { EntityUserPermissionList } from "../permissions";

export interface NoteInput {
  name: string
  content: string
  private: boolean
  permissions: EntityUserPermissionList
}