export interface SpellInput {
  name: string
  range: string
  components: string[]
  materials: string
  ritual: boolean
  concentration: boolean
  duration: string
  casting_time: string
  level: number
  school: string
  description: string
  higher_levels: string
}

export interface Spell extends SpellInput {
  id: number
}