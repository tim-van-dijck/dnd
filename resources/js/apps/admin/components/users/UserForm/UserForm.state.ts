import { UserErrors, UserInput } from "@dnd/types";
import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMessageBus } from "../../../../../services/messages";
import { useUserRepository } from "../../../repositories/UserRepository";
import { useUpdateField } from "../../../utils";

export const useUserFormState = (id?: number) => {
  const userRepository = useUserRepository()
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const [ user, setUser ] = useState<UserInput | null>(null)
  const [ errors, setErrors ] = useState<UserErrors>({})

  const updateField = useUpdateField(user)

  useEffect(() => {
    if (id) userRepository.find(id).then((user) => setUser(user))
  }, [ id ])

  const onUpdate = (field: string, value) => {
    setUser(updateField(field, value))
  }

  const submit = (e: FormEvent) => {
    e.preventDefault()
    if (user === null) return Promise.resolve()

    const promise = id ? userRepository.update(id, user) : userRepository.store(user)
    return promise
      .then(() => navigate('/users'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return { user, errors, submit, onUpdate }
}