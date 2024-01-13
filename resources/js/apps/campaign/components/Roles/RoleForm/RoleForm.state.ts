import { Action } from "@dnd/types";
import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMessageBus } from "../../../../../services/messages";
import { Permission, RoleInput } from "../../../../../types/roles";
import { useUpdateField } from "../../../../admin/utils";
import { useRoleRepository } from "../../../repositories/RoleRepository";

export const useRoleFormState = (id?: number) => {
  const [ errors, setErrors ] = useState<Record<string, string[]>>({})
  const [ input, setInput ] = useState<RoleInput | null>(null)
  const [ selected, setSelected ] = useState(parseAllSelectedPermissions(input))
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const roleRepository = useRoleRepository()

  useEffect(() => {
    roleRepository.loadPermissions()
      .then((permissionList) => {
        if (id) roleRepository.find(id)
          .then(({ name, permissions }) => {
            const fetched = {
              name,
              permissions: Object.fromEntries(permissions.map(({ id, ...permission }) => [ id, permission ]))
            }
            setInput(fetched)
            setSelected(parseAllSelectedPermissions(fetched))
          })
        else {
          setInput(getEmptyRole(permissionList || []))
        }
      })
  }, [])

  const updateField = useUpdateField(input)
  const update = (field: string, value) => setInput(updateField(field, value))

  const updatePermission = (permissionId: string, type: string, value: boolean) => {
    const permissions = { ...input?.permissions }
    permissions[permissionId][type] = value

    setInput({ ...input, permissions })
    setSelected(parseAllSelectedPermissions(input))
  }

  const save = (e: FormEvent) => {
    e.preventDefault()

    const data = {
      ...input,
      permissions: Object.entries(input?.permissions || {})
        .map(([ id, permission ]) => (
          { ...permission, id }
        ))
    }
    const promise = id ? roleRepository.update(id, data) : roleRepository.store(data)

    return promise
      .then(() => navigate('/roles'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  const selectAll = (action: Action) => {
    const ids = Object.keys(input?.permissions || {})
    for (const id of ids) {
      updatePermission(id, action, !selected[action])
    }
  }

  return {
    errors,
    input,
    permissions: roleRepository.permissions || [],
    save,
    selected,
    selectAll,
    update,
    updatePermission
  }
}

const getEmptyRole = (permissions: Permission[]): RoleInput => {
  return {
    name: '',
    permissions: Object.fromEntries(permissions.map(({ id }) => [
      id,
      { view: false, create: false, edit: false, delete: false }
    ]))
  }
}

const parseAllSelectedPermissions = (input: RoleInput | null) => {
  if (input === null) return {
    view: false,
    create: false,
    edit: false,
    delete: false
  }

  const permissions = Object.entries(input.permissions).map(([ id, permission ]) => permission)
  return {
    view: permissions.every((permission) => permission.view),
    create: permissions.every((permission) => permission.create),
    edit: permissions.every((permission) => permission.edit),
    delete: permissions.every((permission) => permission.delete)
  }
}