import { QuestObjectiveInput } from "@dnd/types";
import { MouseEvent, useState } from "react";
import { v4 as uuid } from 'uuid'
import { ObjectiveInputItem } from "./types";

export const useObjectivesState = (
  objectives: QuestObjectiveInput[],
  onChange: (objectives: QuestObjectiveInput[]) => void
) => {
  const [ input, setInput ] = useState<ObjectiveInputItem[]>(objectives.map((o) => formatQuestObjectiveInput(o)))

  const addObjective = (e: MouseEvent) => {
    e.preventDefault()
    setInput([ ...input, formatQuestObjectiveInput({ ...emptyObjective }) ])
  }

  const update = (objectiveKey: string, value: QuestObjectiveInput) => {
    const updated = input.map(({ key, objective }) => (
      {
        key,
        objective: objectiveKey === key ? value : objective
      }
    ))
    setInput(updated)
    onChange(updated.map(({ objective }) => objective))
  }

  const removeObjective = (index: number) => {
    setInput(input.filter((objective) => input.indexOf(objective) !== index))
  }

  return {
    addObjective,
    removeObjective,
    input,
    update
  }
}

const emptyObjective: QuestObjectiveInput = {
  name: '',
  optional: false
}

const formatQuestObjectiveInput = (objective: QuestObjectiveInput, key?: string): ObjectiveInputItem => (
  {
    key: key || uuid(),
    objective
  }
)