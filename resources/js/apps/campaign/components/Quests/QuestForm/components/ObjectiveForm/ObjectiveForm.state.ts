import { QuestObjectiveInput } from "@dnd/types";
import { debounce } from 'lodash'
import { useState } from "react";

export const useObjectiveFormState = (
  objective: QuestObjectiveInput,
  onChange: (objective: QuestObjectiveInput) => void
) => {
  const [ input, setInput ] = useState<QuestObjectiveInput>(objective)

  const update = debounce((field: string, value) => {
    const updated = { ...input, [field]: value }
    setInput(updated)
    onChange(updated)
  }, 300)

  return {
    input,
    update
  }
}