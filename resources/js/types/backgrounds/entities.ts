import { ClassFeature } from '../classes'
import { Proficiency } from '../proficiencies'
import { Language } from '../races'

export interface Background {
  id: number
  name: string
  description: string
  instrument_choices: number
  tool_choices: number
  language_choices: number

  languages: Language[]
  proficiencies: Proficiency[]
  features: ClassFeature[]
}