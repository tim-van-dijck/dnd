import { Ability, Spell } from "@dnd/types";
import { Proficiency } from "../proficiencies";

export interface CharacterClass {
  id: number
  name: string
  description: string
  subclass_flavor: string
  subclass_level: number
  hit_die: number
  instrument_choices: number
  skill_choices: number
  tool_choices: number
  saving_throws: Ability[]
  spellcaster: boolean
  spellcasting_ability?: string

  subclasses: Subclass[]
  proficiencies: Proficiency[]
  spells: Spell[]
  levels: ClassLevel[]
  features: ClassFeature[]
}

export interface CharacterClassSelection {
  id: number
  name: string
  class_id: number
  subclass: Subclass
  subclass_id: number
  level: number
  features: ClassFeature[]
}

export interface Subclass {
  id: number
  class_id: number
  name: string
  description: string
  spellcaster: boolean

  proficiencies: Proficiency[]
  features: ClassFeature[]
  spells: Spell[]
}

export type ClassFeature = {
  id: number
  name: string
  description: string
  level: number
  optional: boolean
  choose: number
  choices?: ClassFeature[]
}

export interface ClassLevel {
  id: number
  class_id: number
  subclass_id: number
  level: number
  cantrips_known: number
  spells_known: number
  spell_slots_level_1: number
  spell_slots_level_2: number
  spell_slots_level_3: number
  spell_slots_level_4: number
  spell_slots_level_5: number
  spell_slots_level_6: number
  spell_slots_level_7: number
  spell_slots_level_8: number
  spell_slots_level_9: number
  class_specific?: Record<string, any>
}