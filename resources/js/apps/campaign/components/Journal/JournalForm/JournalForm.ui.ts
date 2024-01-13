import { JournalEntryInput } from "@dnd/types";
import { useNavigate } from "react-router-dom";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useJournalFormUI = (id: number | undefined, entry: JournalEntryInput | null) => {
  const title = id ? `Edit ${entry?.title || 'entry'}` : 'Create new entry'
  const navigate = useNavigate()
  const { AuthRepository } = useCampaignRepositories()
  const redirect = () => navigate('/notes')

  return { can: AuthRepository.can, redirect, title }
}