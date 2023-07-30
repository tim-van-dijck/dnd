import { InputProps } from "../../types";

export interface InputBooleanProps extends InputProps {
  initialValue?: boolean
  onChange: (value: boolean) => void
}