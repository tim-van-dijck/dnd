import { MouseEvent as ReactMouseEvent, useState } from 'react'
import { PlayerCharacterDetailTab, PlayerCharacterDetailTabConfig } from './PlayerCharacterDetail.types'

export const usePlayerCharacterDetailTabs = (isSpellcaster: boolean) => {
  const [ tab, setTab ] = useState<PlayerCharacterDetailTab>('character')

  const tabs: PlayerCharacterDetailTabConfig[] = [
    {
      key: 'character',
      label: 'Character'
    },
    {
      key: 'class',
      label: 'Class'
    },
    {
      key: 'proficiency',
      label: 'Languages, Skills & Proficiencies'
    },
    {
      key: 'personality',
      label: 'Personality'
    },
    {
      key: 'spells',
      label: 'Spells',
      condition: () => isSpellcaster
    }
  ]

  const navigate = (e: ReactMouseEvent<HTMLAnchorElement, MouseEvent>, tab: PlayerCharacterDetailTab) => {
    e.preventDefault()
    setTab(tab)
  }

  return {
    navigate,
    tab,
    tabs
  }
}