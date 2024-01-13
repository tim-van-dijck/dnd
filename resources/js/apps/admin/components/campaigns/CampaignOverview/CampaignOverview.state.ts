import { useModals } from '../../../modals'
import { useCampaignRepository } from "../../../repositories/CampaignRepository";
import { useEffect } from "react";


export const useCampaignOverviewState = () => {
  const { confirmDelete } = useModals()
  const campaignRepository = useCampaignRepository()

  useEffect(() => void campaignRepository.load(), [])

  return {
    campaignRepository,
    destroy: (campaign) => {
      confirmDelete(
        'campaign',
        () => campaignRepository.destroy(campaign).then(() => campaignRepository.load())
      )
    }
  }
}

