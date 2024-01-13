import { LocationInput } from "@dnd/types";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useLocationFormUI = (input: LocationInput, id?: number) => {
  const { AuthRepository } = useCampaignRepositories()
  const [ tab, setTab ] = useState<'details' | 'permissions'>('details')
  const navigate = useNavigate()
  return {
    can: AuthRepository.can,
    tab,
    setTab,
    redirect: () => navigate('/locations'),
    title: id ? `Edit ${input?.name || 'location'}` : 'Add location'
  }
}