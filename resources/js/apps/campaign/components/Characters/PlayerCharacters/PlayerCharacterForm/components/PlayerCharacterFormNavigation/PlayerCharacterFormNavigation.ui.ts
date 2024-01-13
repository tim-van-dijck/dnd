import { PlayerCharacterInput } from '@dnd/types'
import { PlayerCharacterFormTab } from '../../PlayerCharacterForm.types'

export const usePlayerCharacterFormTabs = (
  input: PlayerCharacterInput,
  spellcaster: boolean,
  tab: PlayerCharacterFormTab,
  onNavigate: (tab: PlayerCharacterFormTab) => void
) => {
  const enabledTabs = getEnabledTabs(input, spellcaster)
  const navigate = (tab: PlayerCharacterFormTab) => {
    if (enabledTabs.includes(tab)) {
      onNavigate(tab)
    }
  }

  return { activeTab: tab, enabledTabs, list: getTabList(spellcaster), navigate }
}

export const useStyling = (enabledTabs: PlayerCharacterFormTab[], activeTab: PlayerCharacterFormTab) => {
  return (tab: PlayerCharacterFormTab) =>
    (
      {
        'uk-button-primary': tab === activeTab,
        'uk-button-default': tab !== activeTab && enabledTabs.includes(tab),
        'disabled': !enabledTabs.includes(tab)
      }
    )
}

const getTabList = (spellcaster: boolean) => [
  {
    key: PlayerCharacterFormTab.DETAILS,
    label: 'Details',
    errorKey: 'info'
  },
  {
    key: PlayerCharacterFormTab.CLASS,
    label: 'Class',
    errorKey: 'classes'
  },
  {
    key: PlayerCharacterFormTab.BACKGROUND,
    label: 'Background'
  },
  {
    key: PlayerCharacterFormTab.PROFICIENCY,
    label: 'Languages, Skills & Proficiencies',
    errorKey: 'proficiencies'
  },
  {
    key: PlayerCharacterFormTab.ABILITY,
    label: 'Abilities',
    errorKey: 'ability_scores'
  },
  {
    key: PlayerCharacterFormTab.PERSONALITY,
    label: 'Personality'
  },
  {
    key: PlayerCharacterFormTab.SPELLS,
    label: 'Spells',
    condition: spellcaster
  }
]

const getEnabledTabs = (input: PlayerCharacterInput, spellcaster: boolean): PlayerCharacterFormTab[] => {
  const enabled = [ PlayerCharacterFormTab.DETAILS ]
  if (input.info.hasOwnProperty('race_id') && input.info.race_id != null) {
    enabled.push(PlayerCharacterFormTab.CLASS)
    if (input.classes?.some((chosenClass) => !!chosenClass.class_id)) {
      enabled.push(...[
        PlayerCharacterFormTab.ABILITY,
        PlayerCharacterFormTab.PROFICIENCY,
        PlayerCharacterFormTab.PERSONALITY,
        PlayerCharacterFormTab.BACKGROUND,
        ...(
          spellcaster ? [ PlayerCharacterFormTab.SPELLS ] : []
        )
      ])
    }
  }
  return enabled
}