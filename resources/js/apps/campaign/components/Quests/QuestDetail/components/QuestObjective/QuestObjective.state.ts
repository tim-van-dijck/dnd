import { QuestObjective } from "@dnd/types";
import { useState } from "react";
import { useQuestRepository } from "../../../../../repositories/QuestRepository";

export const useQuestObjective = (questId: number, objective: QuestObjective) => {
  const questRepository = useQuestRepository()
  const [ status, setStatus ] = useState<number>(objective.status)

  const complete = () => {
    const updated = status == 1 ? 0 : 1
    return questRepository.toggleObjective(questId, objective.id, updated)
      .then(() => setStatus(updated))
  }

  const fail = () => {
    const updated = status == 2 ? 0 : 2
    return questRepository.toggleObjective(questId, objective.id, updated)
      .then(() => setStatus(updated))
  }

  return {
    complete,
    fail,
    status
  }
}