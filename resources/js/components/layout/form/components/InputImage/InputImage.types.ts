import { InputProps } from "../../types";

export interface InputImageProps extends InputProps {
  initialValue?: string
  onChange: (value: File | null) => void
}