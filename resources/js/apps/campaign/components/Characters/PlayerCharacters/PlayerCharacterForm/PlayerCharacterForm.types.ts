import { Action, PlayerCharacterInput } from '@dnd/types'
import { Dispatch, SetStateAction } from 'react'

export type PlayerCharacterFormViewProps = {
  state: {
    errors: Record<string, string[]>
    id?: number
    input: PlayerCharacterInput | null
    save: () => Promise<void>
    update: (field: string, value: unknown) => void
  }
  ui: {
    can: (permission: Action, entity: string, id?: number | null) => boolean
    loaded: boolean
    navigation: {
      isFormTabActive: (tab: PlayerCharacterFormTab) => boolean
      page: PlayerCharacterFormPage
      setPage: Dispatch<SetStateAction<PlayerCharacterFormPage>>
      tab: PlayerCharacterFormTab
      setTab: Dispatch<SetStateAction<PlayerCharacterFormTab>>
      nextOrSave: (condition: boolean, tab: PlayerCharacterFormTab) => void
    }
    spellcaster: boolean
    title: JSX.Element | string
  }
}

export enum PlayerCharacterFormTab {
  DETAILS = 'details',
  CLASS = 'class',
  BACKGROUND = 'background',
  PROFICIENCY = 'proficiency',
  ABILITY = 'ability',
  PERSONALITY = 'personality',
  SPELLS = 'spells'
}

export enum PlayerCharacterFormPage {
  FORM = 'form',
  PERMISSIONS = 'permissions'
}