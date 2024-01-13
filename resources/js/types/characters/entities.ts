import { CharacterClassSelection } from '../classes/entities'
import { EntityUserPermissionList } from '../permissions'
import { Proficiency } from '../proficiencies'
import { Language, Race } from '../races'
import { Subrace } from '../races/subrace'
import { Spell } from '../spells'
import { PlayerCharacterInputInfo } from './input'

export interface PlayerCharacter extends PlayerCharacterBase {
  id: number
  campaign_id: number
  owner_id: number | undefined
  race: CharacterRace
  classes: CharacterClassSelection[]
  spells?: {
    cantrips?: Spell[]
    spells?: Spell[]
  }
  proficiencies?: {
    languages?: Language[]
    skills?: Proficiency[]
    tools?: Proficiency[]
    instruments?: Proficiency[]
  }
  created_at: Date
  updated_at: Date
}

export interface CharacterRace extends Race {
  subrace?: Subrace
}

export interface PlayerCharacterBase {
  id?: number
  campaign_id?: number
  background_id: number | undefined
  owner_id?: number
  type: 'player'
  ability_scores: Record<Ability, number>
  info: PlayerCharacterInfo
  personality: {
    trait: string
    ideal: string
    bond: string
    flaw: string
  }

  permissions?: EntityUserPermissionList
}

interface PlayerCharacterInfo extends PlayerCharacterInputInfo {
  inventory_id?: number | undefined
  inventory?
}


export type Ability = 'STR' | 'DEX' | 'CON' | 'INT' | 'WIS' | 'CHA'
export type Alignment = 'LG' | 'NG' | 'CG' | 'LN' | 'TN' | 'CN' | 'LE' | 'NE' | 'CE'