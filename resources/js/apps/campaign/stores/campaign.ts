import { Campaign, CampaignLog } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";

type CampaignState = {
  campaign: Campaign | null
  logs: CampaignLog[]
}

const initialState: CampaignState = {
  campaign: null,
  logs: []
}

export const campaignSlice = createSlice({
  name: 'campaign',
  initialState,
  reducers: {
    setCampaign: (state, action) => {
      state.campaign = action.payload
    },
    setLogs: (state, action) => {
      state.logs = action.payload
    }
  }
})

export const { setCampaign, setLogs } = campaignSlice.actions

export default campaignSlice.reducer