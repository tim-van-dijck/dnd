export type Action = 'view' | 'create' | 'edit' | 'delete'

export type UserPermissions = Record<Action, boolean | undefined>

export type EntityUserPermissionList = Record<number, UserPermissions>

export type EntityPermissions = Record<number, EntityUserPermissionList>