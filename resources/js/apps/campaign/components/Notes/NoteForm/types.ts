import { Action, NoteInput } from "@dnd/types";
import { FormEvent } from "react";

export interface NoteFormViewProps {
  state: {
    errors: Record<string, string[]>
    id?: number
    input: NoteInput | null
    save: (event: FormEvent) => void
    update: (field: string, value) => void
  }
  ui: {
    can: (permission: Action, entity: string, id?: number | null) => boolean
    title: string
    redirect: () => void
    tab: 'details' | 'permissions'
    setTab: (tab: 'details' | 'permissions') => void
  }
}