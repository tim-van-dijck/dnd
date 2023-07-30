export interface ProficiencyModalProps {
  selected: number[]
  onChange: (value) => void
}

export type ProficiencyInput = {
  id: number
  optional: boolean
}
