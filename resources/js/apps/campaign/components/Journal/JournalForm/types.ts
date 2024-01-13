import { Action, JournalEntryInput } from "@dnd/types";
import { FormEvent } from "react";

export interface JournalFormViewProps {
  state: {
    errors: Record<string, string[]>
    id?: number
    input: JournalEntryInput | null
    save: (event: FormEvent) => void
    update: (field: string, value) => void
  }
  ui: {
    can: (permission: Action, entity: string, id?: number | null) => boolean
    title: string
    redirect: () => void
  }
}