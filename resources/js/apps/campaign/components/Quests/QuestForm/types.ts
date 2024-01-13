import { Action, QuestInput } from "@dnd/types";
import { FormEvent } from "react";

export interface QuestFormProps {

}

export interface QuestFormViewProps {
  state: {
    id?: number
    input: QuestInput | null
    errors: Record<string, string[]>
    save: (event: FormEvent) => void
    update: (field: string, value) => void
  }
  ui: {
    title: string
    can: (permission: Action, entity: string, id?: number | null) => boolean
    tab: 'details' | 'permissions'
    setTab: (tab: 'details' | 'permissions') => void
    redirect: () => void
  }
}