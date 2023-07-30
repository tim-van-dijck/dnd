import { LanguageBase, RaceBase, Trait } from "./entities";

export interface LanguageSelection extends LanguageBase {
  optional: boolean
}

export interface TraitSelection {
  id?: number
  name?: string | null
  description?: string | null
}

export interface RaceInput extends RaceBase {
  traits: Trait[] | Pick<Trait, 'id'>[]
  ability_bonuses: AbilityBonusSelection[]
  languages: LanguageSelection[]
}

export interface AbilityBonusSelection {
  id: number
  ability: string
  bonus: number
  optional: boolean
}