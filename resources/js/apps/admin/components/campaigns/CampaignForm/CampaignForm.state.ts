import { useCampaignRepository } from "../../../repositories/CampaignRepository";
import { useEffect, useState } from "react";
import { CampaignInput } from "@dnd/types";
import { useNavigate } from "react-router-dom";
import { useUpdateField } from "../../../utils";
import { CampaignErrors } from "./types";
import { useMessageBus } from "../../../../../services/messages";

export const useCampaignFormState = (id) => {
  const campaignRepository = useCampaignRepository()
  const messageBus = useMessageBus()
  const [ campaign, setCampaign ] = useState<CampaignInput | null>(null)
  const [ errors, setErrors ] = useState<CampaignErrors>({})
  const updateField = useUpdateField(campaign)
  const navigate = useNavigate()

  const onUpdate = (field: string, value) => {
    setCampaign(updateField(field, value))
  }

  useEffect(() => void campaignRepository.find(id).then(({ id, ...campaign }) => setCampaign(campaign)), [])

  const save = () => {
    if (campaign === null) return Promise.resolve()

    const promise = id ? campaignRepository.update(id, campaign) : campaignRepository.store(campaign)
    promise
      .then(() => navigate('/campaigns'))
      .catch((error) => {
        setErrors(error.response.data.errors)
        messageBus.error(error.response.data.message)
      })
  }

  return { campaign, errors, onUpdate, save }
}