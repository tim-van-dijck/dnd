import { PlayerCharacterInputInfo } from '@dnd/types'
import { IFormOption } from '../../../../../../../../components/layout/form/types'

export type PlayerCharacterFormDetailsTabProps = {
  value: PlayerCharacterInputInfo
  errors: Record<string, string[]>
  onUpdate: (info: PlayerCharacterInputInfo) => void
  onNext: () => void
}

export type PlayerCharacterFormDetailsTabViewProps = {
  state: {
    alignments: IFormOption[]
    errors: Record<string, string[]>
    input: PlayerCharacterInputInfo
    isOwner: boolean
    races: IFormOption[]
    subraces: IFormOption[]
    update: (field: string, value: string | number | boolean) => void
    users: IFormOption[]
    next: () => void
  }
}