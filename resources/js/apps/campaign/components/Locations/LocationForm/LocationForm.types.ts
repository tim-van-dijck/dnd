import { LocationInput } from "@dnd/types";


export interface LocationFormViewProps {
  state: {
    input: LocationInput,
    errors: Record<string, string[]>
    save: () => void
    update: (field: string, value) => void
  }
  ui: {
    can: () => void
    tab: 'details' | 'permissions'
    setTab: (tab: 'details' | 'permissions') => void
    title: string
  }
}