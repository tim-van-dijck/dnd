import { QuestBase } from "./entities";

export interface QuestInput extends QuestBase {
  objectives: QuestObjectiveInput[]
}

export interface QuestObjectiveInput {
  name: string
  optional: boolean
}