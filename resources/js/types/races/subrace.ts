import { AbilityBonus, Proficiency, Trait } from '@dnd/types'

export interface Subrace {
  id: number
  race_id: number
  name: string
  description: string
  proficiencies: Proficiency[]
  abilities: AbilityBonus[]
  traits: Trait[]
  optional_ability_bonuses: number
  optional_languages: number
  optional_proficiencies: number
  optional_traits: number
}