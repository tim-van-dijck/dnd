import { IFormOption, InputProps } from "../../types";

export interface InputCheckboxProps extends InputProps {
  initialValue?: Array<string | number>
  onChange: (value: Array<string | number>) => void
  options: IFormOption[]
}