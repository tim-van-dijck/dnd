import { InputProps } from "../../types";

export interface InputTextProps extends InputProps {
  initialValue?: string
  onChange: (value: string) => void
  multiline?: boolean
}