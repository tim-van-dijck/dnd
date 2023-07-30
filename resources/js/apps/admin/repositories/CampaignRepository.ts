import { Campaign, CampaignInput } from "@dnd/types";
import axios from "axios";
import { PaginatedData, useRepository } from "../../../repositories/BaseRepository";
import { useMessageBus } from "../../../services/messages";
import { useAdminDispatch, useAdminSelector } from "../store";
import { setCampaigns, setUserCampaigns } from "../stores/campaigns";
import { CampaignRepositoryInterface } from "./types";

export const useCampaignRepository = (): CampaignRepositoryInterface => {
  const url = '/api/admin/campaigns'
  const messageBus = useMessageBus()
  const repo = useRepository<Campaign>(url)
  const campaigns = useAdminSelector(state => state.campaigns.campaigns)
  const userCampaigns = useAdminSelector(state => state.campaigns.userCampaigns)
  const dispatch = useAdminDispatch()

  const page = (number: number): Promise<PaginatedData<Campaign>> | null => {
    if (campaigns != null && number > 0 && number <= campaigns.meta.last_page && number >= 1) {
      return repo.page(number)?.then?.((records) => {
        dispatch(setCampaigns(records))
        return records
      }) || null
    }
    return null
  }

  return {
    campaigns,
    userCampaigns,

    previous: () => (
      campaigns?.meta?.current_page > 1
    ) ? page(campaigns?.meta?.current_page - 1) : null,
    page,
    next: () =>
      (
        campaigns?.meta?.current_page < campaigns?.meta?.last_page
      ) ? page(campaigns?.meta?.current_page + 1) : null,
    load: (): Promise<PaginatedData<Campaign>> => repo.load().then((records) => {
      dispatch(setCampaigns(records));
      return records
    }),
    loadForUser(userId: number) {
      if (userCampaigns.hasOwnProperty(userId)) {
        return Promise.resolve(userCampaigns[userId])
      }
      return axios.get(`${url}?filters[user_id]=${userId}`)
        .then((response) => {
          dispatch(setUserCampaigns({ ...userCampaigns, [userId]: response.data.data }))
          return response.data.data
        })
    },
    find: (id: number): Promise<Campaign> => axios.get(`${url}/${id}`).then((response) => response.data),

    store: (campaign: CampaignInput) => axios.post(url, campaign).then(() => messageBus.success('Campaign saved!')),
    update: (id: number, campaign: CampaignInput) =>
      axios.put(`${url}/${id}`, campaign).then(() => messageBus.success('Campaign saved!')),
    destroy: (id: number) => axios.delete(`${url}/${id}`)
      .then(() => repo.load().then(() => messageBus.success('Campaign successfully deleted!')))
  }
}