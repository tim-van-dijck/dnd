import { IFormOption, IFormOptionGroup, InputProps } from '../../types'

export interface SelectProps extends InputProps {
  initialValue?: string | number | null
  errors?: string | string[]
  onChange: (value: string | number) => void
  options: (IFormOption | IFormOptionGroup)[]
  emptyLabel?: string | JSX.Element
}