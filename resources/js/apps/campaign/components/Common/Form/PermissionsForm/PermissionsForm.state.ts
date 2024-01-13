import { Action } from "@dnd/types";
import { useEffect, useState } from "react";
import { useCampaignRepositories } from "../../../../providers/CampaignRepositoryProvider";

export const usePermissionsFormState = (entity: string, onChange: (permissions) => void, id?: number, value?) => {
  const { UserRepository, PermissionRepository } = useCampaignRepositories()
  const [ input, setInput ] = useState<Record<number, any> | null>(value || null)
  const [ override, setOverride ] = useState<boolean>(false)
  const [ selected, setSelected ] = useState({
    view: false,
    edit: false,
    delete: false
  })

  useEffect(() => {
    Promise.all([
      UserRepository.load(),
      PermissionRepository.fetch({ entity, id })
    ]).then(([ users, permissions ]) => {
      init(users, permissions)
    })
  }, [])

  // useEffect(() => setInput(value ? input : {}), [ JSON.stringify(override) ])
  useEffect(() => onChange(input), [ JSON.stringify(input) ])

  const selectAll = (action: Action) => {
    const updated = { ...input }
    const newState = !selected[action]
    setSelected({ ...selected, [action]: newState })
    for (const userId in updated) {
      updated[userId][action] = newState
    }
    setInput(updated)
  }

  const init = (users, permissions) => {
    const initial = permissions || {}
    setOverride(Object.keys(initial).length > 0)

    for (const user of users) {
      if (!initial.hasOwnProperty(user.id)) {
        initial[user.id] = {
          view: false,
          edit: false,
          delete: false
        }
      }
    }
    setInput(initial)
  }

  const toggle = (userId: number, action: Action, value: boolean) => {
    const permissions = {
      ...input,
      [userId]: {
        ...(
          input?.[userId] || {}
        ),
        [action]: value
      }
    }
    setInput(permissions)
  }

  return {
    input, override, selected, setOverride, selectAll, toggle, users: UserRepository.users
  }
}