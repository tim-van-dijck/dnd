import { Campaign } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";

type CampaignState = {
  campaigns: PaginatedData<Campaign> | null
  userCampaigns: Record<number, Campaign[]>
}

const initialState: CampaignState = {
  campaigns: null,
  userCampaigns: {}
}

export const campaignSlice = createSlice({
  name: 'campaigns',
  initialState,
  reducers: {
    setCampaigns: (state, action) => void (
      state.campaigns = action.payload
    ),
    setUserCampaigns: (state, action) => void (
      state.userCampaigns = action.payload
    )
  }
})

export const { setCampaigns, setUserCampaigns } = campaignSlice.actions

export default campaignSlice.reducer