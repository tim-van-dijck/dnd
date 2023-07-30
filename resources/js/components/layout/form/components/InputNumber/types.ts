import { InputProps } from "../../types";

export interface InputNumberProps extends InputProps {
  initialValue?: number
  onChange: (value: number) => void
  min?: number
  max?: number
  step?: number
}