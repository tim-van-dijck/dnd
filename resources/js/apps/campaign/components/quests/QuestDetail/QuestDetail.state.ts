import { Quest } from "@dnd/types";
import { useEffect, useState } from "react";
import { useQuestRepository } from "../../../repositories/QuestRepository";

export const useQuestDetailState = (id: number) => {
  const questRepository = useQuestRepository()
  const [ quest, setQuest ] = useState<Quest | null>(null)

  useEffect(() => void questRepository.find(id).then(setQuest), [ id ])

  return {
    quest,
    objectives: quest?.objectives?.filter((objective) => !objective.optional) || [],
    optional: quest?.objectives?.filter((objective) => objective.optional) || []
  }
}