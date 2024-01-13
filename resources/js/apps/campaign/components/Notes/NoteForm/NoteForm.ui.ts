import { NoteInput } from "@dnd/types";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useNoteFormUI = (input: NoteInput | null, id?: number) => {
  const title = id ? `Edit ${input?.name || 'note'}` : 'Create new note'
  const navigate = useNavigate()
  const { AuthRepository } = useCampaignRepositories()
  const redirect = () => navigate('/notes')
  const [ tab, setTab ] = useState<'details' | 'permissions'>('details')

  return { can: AuthRepository.can, redirect, setTab, tab, title }
}