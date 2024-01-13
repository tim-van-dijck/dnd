import { PlayerCharacterInputInfo } from '@dnd/types'
import { useEffect, useState } from 'react'
import { IFormOption } from '../../../../../../../../components/layout/form/types'
import { useUpdateField } from '../../../../../../../admin/utils'
import { useCampaignRepositories } from '../../../../../../providers/CampaignRepositoryProvider'

export const usePlayerCharacterFormDetailState = (
  info: PlayerCharacterInputInfo,
  onUpdate: (value: PlayerCharacterInputInfo) => void
) => {
  const [ input, setInput ] = useState<PlayerCharacterInputInfo>(info)
  const { AuthRepository, PlayerCharacterRepository, UserRepository } = useCampaignRepositories()
  const updateField = useUpdateField(input)
  const update = (field: string, value: unknown) => setInput(updateField(field, value))

  useEffect(() => {
    if (info.owner_id) void UserRepository.load()
  }, [])

  useEffect(() => {
    onUpdate(input)
  }, [ input ])

  const isOwner = AuthRepository.user!.id === input?.owner_id || AuthRepository.hasRole(AuthRepository.user, 'Admin')
  const races: IFormOption[] = PlayerCharacterRepository.races?.map(({ id, name }) => (
    { label: name, value: id, type: 'option' }
  )) || []
  const subraces: IFormOption[] = PlayerCharacterRepository.races?.find(({ id }) => id === input?.race_id)
    ?.subraces
    ?.map(({ id, name }) => (
      { label: name, value: id }
    )) || []
  const users: IFormOption[] = UserRepository.users?.map(({ id, name }) => (
    { label: name, value: id }
  )) || []

  return { input, isOwner, races, subraces, update, users }
}

export const alignments: IFormOption[] = [
  { value: 'LG', label: 'Lawful Good' },
  { value: 'NG', label: 'Neutral Good' },
  { value: 'CG', label: 'Chaotic Good' },
  { value: 'LN', label: 'Lawful Neutral' },
  { value: 'TN', label: 'True Neutral' },
  { value: 'CN', label: 'Chaotic Neutral' },
  { value: 'LE', label: 'Lawful Evil' },
  { value: 'NE', label: 'Neutral Evil' },
  { value: 'CE', label: 'Chaotic Evil' }
]