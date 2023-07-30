import { LocationInput } from "@dnd/types";
import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMessageBus } from "../../../../../services/messages";
import { useUpdateField } from "../../../../admin/utils";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useLocationFormState = (id: number | undefined) => {
  const [ errors, setErrors ] = useState<Record<string, string[]>>({})
  const [ input, setInput ] = useState<LocationInput | null>(null)
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const { AuthRepository, LocationRepository } = useCampaignRepositories()

  const updateField = useUpdateField(input)
  const update = (field: string, value) => setInput(updateField(field, value))

  useEffect(() => {
    if (id) LocationRepository.find(id)
      .then(({ name, description, type, map, private: isPrivate, permissions }) => setInput({
        name,
        description,
        type,
        map,
        private: isPrivate,
        permissions
      }))
    else setInput({ ...emptyLocation })
  }, [])

  const save = (e: FormEvent) => {
    e.preventDefault()
    const location: LocationInput = { ...input } as LocationInput
    if (!AuthRepository.can('edit', 'role')) delete location.permissions
    const promise = id ? LocationRepository.update(id, location) : LocationRepository.store(location)

    return promise
      .then(() => navigate('/locations'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return { errors, input, save, update }
}

const emptyLocation: LocationInput = {
  name: '',
  description: '',
  type: '',
  map: '',
  private: false
}