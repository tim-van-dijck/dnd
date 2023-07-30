import { InputProps } from "../../types";

export interface InputEmailProps extends InputProps {
  initialValue?: string
  onChange: (value: string) => void
}