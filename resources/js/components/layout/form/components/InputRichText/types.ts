import { InputProps } from "../../types";

export interface InputRichTextProps extends InputProps {
  initialValue?: string
  onChange: (value: string) => void
  height?: number
}