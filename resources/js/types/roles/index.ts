export interface Role {
  id: number
  campaign_id: number
  name: string
  system: boolean
  permissions: RolePermission[]
  created_at: Date
  updated_at: Date
}

export interface RoleInput {
  name?: string
  system?: boolean
  permissions: PermissionInput
}

export interface PermissionInput {
  [id: number]: {
    view: boolean
    create: boolean
    edit: boolean
    delete: boolean
  }
}

export interface RolePermission {
  id: number
  view: boolean
  create: boolean
  edit: boolean
  delete: boolean
}

export interface Permission {
  id: number
  name: string
}