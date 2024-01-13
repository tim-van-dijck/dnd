import { CharacterClass, CharacterClassSelection, Subclass } from '@dnd/types'

export type PlayerCharacterClassTabProps = {
  classes: CharacterClassSelection[]
  active: boolean
}

export type PlayerCharacterClassTabViewProps = {
  state: {
    loading: boolean
    characterClasses: any[] // CharacterClass
  }
  ui: {
    activeFeatures: Record<number, {
      id: number
      name: string
      description: string
    }>
    setActive: (classId: number, choice: any) => void
  }
}

export interface DisplayClass extends CharacterClass {
  level: number
  subclass?: Subclass
}