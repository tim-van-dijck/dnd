import { CampaignUser } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";

type CampaignUserState = {
  users: PaginatedData<CampaignUser> | null
}

const initialState: CampaignUserState = {
  users: null
}

export const campaignUserSlice = createSlice({
  name: 'campaignUsers',
  initialState,
  reducers: {
    setUsers: (state, action) => {
      state.users = action.payload
    }
  }
})

export const { setUsers } = campaignUserSlice.actions

export default campaignUserSlice.reducer