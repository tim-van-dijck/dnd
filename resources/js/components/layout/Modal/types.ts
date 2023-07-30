import { ReactNode } from "react";

export interface ModalProps {
  id: string

  title?: string | ReactNode
  trigger: JSX.Element
  children: ReactNode
}

export interface ModalViewProps {
  ui: {
    title?: string | ReactNode
  }
  children: ReactNode
}