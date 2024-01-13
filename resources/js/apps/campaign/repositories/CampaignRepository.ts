import axios from "axios";
import { useCampaignDispatch, useCampaignSelector } from "../store";
import { setCampaign, setLogs } from "../stores/campaign";
import { CampaignRepositoryInterface } from "../types/repositories";

export const useCampaignRepository = (): CampaignRepositoryInterface => {
  const campaign = useCampaignSelector(state => state.campaign.campaign)
  const logs = useCampaignSelector(state => state.campaign.logs)
  const dispatch = useCampaignDispatch()

  return {
    campaign,
    logs,
    loadCampaign: () => axios.get('/api/campaign')
      .then((response) => {
        dispatch(setCampaign(response.data.data))
      }),
    loadLogs: () =>
      axios.get('/api/campaign/logs')
        .then((response) => {
          dispatch(setLogs(response.data.data));
        }),
  }
}