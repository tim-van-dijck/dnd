import { QuestObjectiveInput } from "@dnd/types";

export interface ObjectivesProps {
  value: QuestObjectiveInput[]
  errors: Record<string, string[]>
  onChange: (value: QuestObjectiveInput[]) => void
}

export interface ObjectiveInputItem {
  key: string
  objective: QuestObjectiveInput
}