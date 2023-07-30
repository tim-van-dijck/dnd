import { IFormOption, IFormOptionGroup, InputProps } from "../../types";

export interface SelectProps extends InputProps {
  initialValue?: string | number
  errors?: string | string[]
  onChange: (value: string | number) => void
  options: (IFormOption | IFormOptionGroup)[]
  emptyLabel?: string | JSX.Element
}