import { QuestInput } from "@dnd/types";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useQuestFormUI = (input: QuestInput | null, id?: number) => {
  const title = id ? `Edit ${input?.title || 'quest'}` : 'Create new quest'
  const navigate = useNavigate()
  const { AuthRepository } = useCampaignRepositories()
  const redirect = () => navigate('/quests')
  const [ tab, setTab ] = useState<'details' | 'permissions'>('details')

  return { can: AuthRepository.can, redirect, setTab, tab, title }
}