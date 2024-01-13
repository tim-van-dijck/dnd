import { Spell } from '@dnd/types'

export type PlayerCharacterSpellsProps = {
  active: boolean
  spells: {
    cantrips: Spell[]
    spells: Spell[]
  }
}

export type SpellLevelCategory = {
  level: number
  title: string
  spells: Spell[]
}