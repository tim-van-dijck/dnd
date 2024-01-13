import { PlayerCharacterInput } from '@dnd/types'
import { PlayerCharacterFormTab } from '../../PlayerCharacterForm.types'

export type PlayerCharacterFormNavigationProps = {
  errors: Record<string, string[]>
  input: PlayerCharacterInput
  onNavigate: (tab: PlayerCharacterFormTab) => void
  spellcaster: boolean
  tab: PlayerCharacterFormTab
}