import { Language, Proficiency } from '@dnd/types'

export type PlayerCharacterProficiencyTabProps = {
  active: boolean
  proficiencies: {
    armor: Proficiency[]
    instruments: Proficiency[]
    languages: Language[]
    skills: Proficiency[]
    tools: Proficiency[]
    weapons: Proficiency[]
  }
}

export type PlayerCharacterProficiencyTabViewProps = {
  state: {
    armor: Proficiency[]
    instruments: Proficiency[]
    characterLanguages: Language[]
    skills: Proficiency[]
    tools: Proficiency[]
    weapons: Proficiency[]
  }
  ui: {
    loading: boolean
  }
}