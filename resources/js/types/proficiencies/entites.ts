export interface Proficiency extends ProficiencyInput {
  name: string
  type: 'Skills' | 'Tools' | 'Instruments' | 'Armor' | 'Weapons'
  origin_id: number
  origin_type: ProficiencyOriginType
}

export type ProficiencyInput = {
  id: number
  optional: boolean
  origin_id?: number
  origin_type?: ProficiencyOriginType
}

export type ProficiencyOriginType = 'class' | 'subclass' | 'race' | 'subrace' | 'background'