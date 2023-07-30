import UIkit from 'uikit'
import { FormEvent, useState } from "react";
import { User, UserErrors } from "../../../../../../../types";
import { useUserRepository } from "../../../../../repositories/UserRepository";
import { useUpdateField } from "../../../../../utils";

export const useInviteModalState = (onInvite: (user: User) => void) => {
  const userRepository = useUserRepository()
  const [ user, setUser ] = useState<User>({ ...emptyUser })
  const [ errors, setErrors ] = useState<UserErrors>({})
  const updateField = useUpdateField(user)

  const onUpdate = (field: string, value) => setUser(updateField(field, value))

  const submit = (e: FormEvent) => {
    e.preventDefault()
    userRepository.invite(user)
      .then(() => {
        UIkit.modal('#user-modal').hide()
        onInvite(user)
        return user
      })
      .catch((exception) => {
        setErrors(exception.response.data.errors)
      })
  }
  const open = () => {
    setUser({ ...emptyUser })
    UIkit.modal('#user-modal').show()
  }

  const cancel = () => {
    setUser({ ...emptyUser })
    UIkit.modal('#user-modal').hide()
  }

  return { user, errors, submit, open, cancel, onUpdate }
}

const emptyUser: User = {
  email: '',
  admin: false
}