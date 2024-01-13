import { EntityUserPermissionList } from '../permissions'
import { ProficiencyOriginType } from '../proficiencies'
import { Spell } from '../spells'
import { Ability, Alignment, PlayerCharacterBase } from './entities'

export interface CharacterInput {
  background_id: number | null
  owner_id: number | null
  info: CharacterInputInfo
  type: 'player' | 'npc'
  ability_scores: Record<Ability, number>

  permissions?: EntityUserPermissionList
}

interface CharacterInputInfo {
  name: string
  race_id: number | undefined
  subrace_id: number | undefined
  age: string
  alignment: Alignment | undefined
  dead?: boolean
  private?: boolean
  bio?: string
  personality?: {
    ideal?: string
    bond?: string
    flaw?: string
    trait?: string
  }
}

export interface PlayerCharacterInputInfo extends CharacterInputInfo {
  owner_id?: number
  inventory_id?: number | null
}

export interface PlayerCharacterInput extends PlayerCharacterBase {
  info: PlayerCharacterInputInfo
  classes?: CharacterClassInput[]
  proficiencies?: {
    languages?: number[]
    skills?: CharacterProficiencyInput[]
    tools?: CharacterProficiencyInput[]
    instruments?: CharacterProficiencyInput[]
  }
  spells?: {
    cantrips?: Spell[]
    spells?: Spell[]
  }
}

export type CharacterProficiencyInput = {
  id: number
  origin_id: number
  origin_type: ProficiencyOriginType
}

export type CharacterClassInput = {
  class_id: number
  subclass_id?: number
  level: number
  features?: Record<number, Record<number, number>>
}