import { Action } from "@dnd/types";
import { FormEvent } from "react";
import { Permission, RoleInput } from "../../../../../types/roles";

export interface RoleFormViewProps {
  state: {
    errors: RoleErrors
    input: RoleInput | null
    permissions: Permission[]
    selectAll: (action: Action) => void
    selected: {
      view: boolean
      create: boolean
      edit: boolean
      delete: boolean
    }
    save: (e: FormEvent) => void
    updatePermission: (permissionId: number, action: Action, value: boolean) => void
    update: (field: string, value) => void
  }
  ui: {
    actions: Action[]
    can: (permission: Action, entity: string, id?: number | null) => boolean
    redirect: () => void
    title: string
  }
}

export interface RoleErrors {
  name?: string[],
  permissions?: string[]
}