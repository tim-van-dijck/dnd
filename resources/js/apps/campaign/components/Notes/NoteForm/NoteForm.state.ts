import { NoteInput } from "@dnd/types";
import { FormEvent, useEffect, useState } from 'react'
import { useNavigate } from "react-router-dom";
import { useMessageBus } from "../../../../../services/messages";
import { useUpdateField } from "../../../../admin/utils";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useNoteFormState = (id?: number) => {
  const [ errors, setErrors ] = useState<Record<string, string[]>>({})
  const [ input, setInput ] = useState<NoteInput | null>(null)
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const { AuthRepository, NoteRepository } = useCampaignRepositories()

  const updateField = useUpdateField(input)
  const update = (field: string, value) => setInput(updateField(field, value))

  useEffect(() => {
    if (id) NoteRepository.find(id)
      .then(({ name, content, private: isPrivate, permissions }) => setInput({
        name,
        content,
        private: isPrivate,
        permissions
      }))
    else setInput({ ...emptyNote })
  }, [])

  const save = (e: FormEvent) => {
    e.preventDefault()
    if (input === null) return Promise.resolve()

    const note = { ...input } as NoteInput

    if (AuthRepository.can('edit', 'role')) {
      note.permissions = input.permissions || {}
    }

    const promise = id ? NoteRepository.update(id, note) : NoteRepository.store(note)
    return promise
      .then(() => navigate('/notes'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return { errors, input, setErrors, setInput, save, update }
}

const emptyNote: NoteInput = {
  name: '',
  content: '',
  private: false,
  permissions: {}
}