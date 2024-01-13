import { MouseEvent as ReactMouseEvent } from 'react'

export type PlayerCharacterDetailViewProps = {
  state: {
    character
    isSpellcaster: boolean
  }
  ui: {
    navigate: (e: ReactMouseEvent<HTMLAnchorElement, MouseEvent>, tab: PlayerCharacterDetailTab) => void
    tab: PlayerCharacterDetailTab
    tabs: PlayerCharacterDetailTabConfig[]
  }
}

export type PlayerCharacterDetailTab = 'character' | 'class' | 'proficiency' | 'personality' | 'spells'

export type PlayerCharacterDetailTabConfig = {
  key: PlayerCharacterDetailTab
  label: string | JSX.Element
  condition?: () => boolean
}
