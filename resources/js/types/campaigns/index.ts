export interface CampaignInput {
  name: string
  description: string
}

export interface Campaign extends CampaignInput {
  id: number
}

export type CampaignLog = {
  message: string
  created_at: string
}