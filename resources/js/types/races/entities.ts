import { Subrace } from "./subrace";
import { AbilityBonusSelection } from "./input";

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
  ability_bonuses: AbilityBonusSelection[]
  languages: Language[]
  subraces: Subrace[]
}

export interface Proficiency {
  id: number
  name: string
  type: 'skills' | 'tools' | 'instruments'
  optional?: boolean
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


export type RaceAttribute = 'ability_bonuses' | 'feats' | "languages" | 'proficiencies' | 'traits'

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

export type LanguageScript = 'Celestial' | 'Common' | 'Draconic' | 'Dwarvish' | 'Elvish' | 'Infernal' | ''
