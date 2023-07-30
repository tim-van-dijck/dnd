import { CampaignUser, CampaignUserErrors, CampaignUserInput } from "@dnd/types";
import { FormEvent, useEffect, useState } from "react";
import UIkit from "uikit";
import { IFormOption } from "../../../../../../../components/layout/form/types";
import { useMessageBus } from "../../../../../../../services/messages";
import { Role } from "../../../../../../../types/roles";
import { useUpdateField } from "../../../../../../admin/utils";
import { useRoleRepository } from "../../../../../repositories/RoleRepository";
import { useUserRepository } from "../../../../../repositories/UserRepository";

export const useUserFormModalState = (user: CampaignUser | null, onFinish?: () => void) => {
  const userRepository = useUserRepository()
  const roleRepository = useRoleRepository()
  const [ input, setInput ] = useState(getDefaultUserInput([], user))
  const [ errors, setErrors ] = useState<CampaignUserErrors>({})
  const messageBus = useMessageBus()

  const roles = roleRepository.roles?.data || [];
  useEffect(() => {
    setInput(getDefaultUserInput(roles, user))
  }, [ user ])

  useEffect(() => {
    void roleRepository.load()
  }, [])

  const updateField = useUpdateField(input)
  const update = (field: string, value) => setInput(updateField(field, value))

  const save = (e: FormEvent) => {
    e.preventDefault()

    const promise = user ? userRepository.update(user.id, input) : userRepository.invite(input)

    return promise.then(() => {
      setInput(getDefaultUserInput(roles || [], null));
      onFinish?.()
      UIkit.modal('#user-modal').hide()
    }).catch((error) => {
      setErrors(error.response.data.errors)
      messageBus.error(error.response.data.message)
    })
  }

  return {
    errors,
    roles: roles?.map(({ id, name }): IFormOption => (
      { label: name, value: id }
    )) || [],
    save,
    update,
    input
  }
}

const getDefaultUserInput = (roles: Role[], user: CampaignUser | null): CampaignUserInput => {
  return user ?
    { email: user.email, role: roles.find(({ name }) => name === user.role)?.id || '' } :
    { email: '', role: '' }
}