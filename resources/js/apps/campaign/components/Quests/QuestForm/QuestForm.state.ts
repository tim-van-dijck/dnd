import { QuestInput } from '@dnd/types'
import { FormEvent, useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useMessageBus } from '../../../../../services/messages'
import { useUpdateField } from '../../../../admin/utils'
import { useCampaignRepositories } from '../../../providers/CampaignRepositoryProvider'

export const useQuestFormState = (id: number | undefined) => {
  const [ errors, setErrors ] = useState<Record<string, string[]>>({})
  const [ input, setInput ] = useState<QuestInput | null>(null)
  const messageBus = useMessageBus()
  const navigate = useNavigate()
  const { AuthRepository, QuestRepository } = useCampaignRepositories()

  const updateField = useUpdateField(input)
  const update = (field: string, value) => setInput(updateField(field, value))

  useEffect(() => {
    if (id) QuestRepository.find(id)
      .then(({ title, description, private: isPrivate, permissions, objectives }) => setInput({
        title,
        description,
        objectives,
        private: isPrivate,
        permissions
      }))
    else setInput({ ...emptyQuest })
  }, [])

  const save = (e: FormEvent): Promise<void> => {
    e.preventDefault()
    if (input === null) return Promise.resolve()

    const quest = { ...input } as QuestInput

    if (AuthRepository.can('edit', 'role')) {
      quest.permissions = input.permissions || {}
    }

    const promise = id ? QuestRepository.update(id, quest) : QuestRepository.store(quest)
    return promise
      .then(() => navigate('/quests'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return {
    errors,
    input,
    save,
    update
  }
}

const emptyQuest: QuestInput = {
  title: '',
  description: '',
  objectives: [
    { name: '', optional: false }
  ],
  private: false,
  permissions: {}
}