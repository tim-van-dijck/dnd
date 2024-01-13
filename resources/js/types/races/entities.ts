import { Ability } from '../characters'
import { Proficiency } from '../proficiencies'
import { AbilityBonusSelection } from './input'
import { Subrace } from './subrace'

export interface RaceBase {
  name: string
  description: string
  size: string
  speed: number
  feats: Feat[]
  proficiencies: Proficiency[]
  optional_ability_bonuses: number
  optional_feats: number
  optional_languages: number
  optional_proficiencies: number
  optional_traits: number
}

export interface Race extends RaceBase {
  id: number
  traits: Trait[]
  abilities: AbilityBonus[]
  ability_bonuses: AbilityBonusSelection[]
  languages: Language[]
  subraces: Subrace[]
}

export interface Trait {
  id: number
  name: string
  description: string
  optional?: boolean
}

export interface Feat {
  id: number
}


export type RaceAttribute = 'ability_bonuses' | 'feats' | 'languages' | 'proficiencies' | 'traits'

export interface Language extends LanguageBase {
  name: string
  type: 'Standard' | 'Exotic'
  script: LanguageScript
  optional?: string
}

export interface LanguageBase {
  id: number
}

export interface Trait {
  id: number
  name: string
  description: string
}

export type AbilityBonus = {
  ability: Ability
  bonus: number
  optional: boolean
}

export type LanguageScript = 'Celestial' | 'Common' | 'Draconic' | 'Dwarvish' | 'Elvish' | 'Infernal' | ''
