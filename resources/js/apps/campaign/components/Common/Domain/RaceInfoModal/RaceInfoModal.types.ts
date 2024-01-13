import { Race, Trait } from '@dnd/types'
import { Subrace } from '../../../../../../types/races/subrace'

export type RaceInfoModalViewProps = {
  state: {
    races: Race[]
  }
  ui: {
    open: () => void
    tab: {
      key: RaceInfoModalTab
      set: (tab: RaceInfoModalTab) => void
    }

    race: DisplayRace | null
    subrace: DisplaySubrace | null
    trait: Trait | null
    subraceTrait: Trait | null

    setActiveRace: (raceId: number) => void
    setActiveSubrace: (subraceId: number) => void
    setActiveRaceTrait: (traitId: number) => void
    setActiveSubraceTrait: (traitId: number) => void
  }
}

export interface DisplayRace extends Omit<Race, 'proficiencies'> {
  ability_scores: string
  proficiencies: {
    armor: string[]
    skills: string[]
    tools: string[]
    weapons: string[]
    optional: string[]
  }
}

export interface DisplaySubrace extends Omit<Subrace, 'proficiencies'> {
  ability_scores: string
  proficiencies: {
    armor: string[]
    skills: string[]
    tools: string[]
    weapons: string[]
    optional: string[]
  }
}

export type RaceInfoModalTab = 'description' | 'traits' | 'subraces'