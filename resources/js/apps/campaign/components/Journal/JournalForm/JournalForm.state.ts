import { JournalEntryInput } from "@dnd/types";
import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMessageBus } from "../../../../../services/messages";
import { useUpdateField } from "../../../../admin/utils";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useJournalFormState = (id: number | undefined) => {
  const [ input, setInput ] = useState<JournalEntryInput | null>(null)
  const [ errors, setErrors ] = useState<Record<string, string[]>>({})
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const { JournalRepository } = useCampaignRepositories()

  const updateField = useUpdateField(input)
  const update = (field: string, value) => setInput(updateField(field, value))

  useEffect(() => {
    if (id) JournalRepository.find(id)
      .then(({ title, content, private: isPrivate }) => setInput({
        title,
        content,
        private: isPrivate
      }))
    else setInput({ title: '', content: '', private: false })
  }, [])

  const save = (e: FormEvent) => {
    e.preventDefault()
    if (input === null) return
    const entry = { ...input }
    const promise = id === undefined ? JournalRepository.store(entry) : JournalRepository.update(id, entry)
    promise
      .then(() => navigate('/journal'))
      .catch((exception) => {
        setErrors(exception.response.data.errors)
        messageBus.error(exception.response.data.message)
      })
  }


  return { errors, input, setErrors, setInput, save, update }
}