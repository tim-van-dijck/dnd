import { QuestObjectiveInput } from "./input";

export interface Quest extends QuestBase {
  id: number
  objectives: QuestObjective[]
  created_at: Date
  updated_at: Date
}

export interface QuestObjective extends QuestObjectiveInput {
  id: number
  status: number
  created_at: Date
  updated_at: Date
}

export interface QuestBase {
  title: string
  description: string
  private: boolean
  permissions: Record<string, string[]>
}