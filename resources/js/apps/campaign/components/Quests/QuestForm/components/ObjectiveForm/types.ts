import { QuestObjectiveInput } from "@dnd/types";

export interface ObjectiveFormProps {
  id: string
  objective: QuestObjectiveInput
  errors: Record<string, string[]>
  canDelete: boolean
  onChange: (value: QuestObjectiveInput) => void
  onDelete: () => void
}