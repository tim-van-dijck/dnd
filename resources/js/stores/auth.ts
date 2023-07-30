import { createSlice } from "@reduxjs/toolkit";
import { Campaign, User } from "../types";

type AuthState = {
  user: User | null
  campaign: Campaign | null
}

const initialState: AuthState = {
  user: null,
  campaign: null
}

export const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    setCampaign: (state, action) => void (
      state.campaign = action.payload
    ),
    setUser: (state, action) => void (
      state.user = action.payload
    )
  }
})

export const { setUser } = authSlice.actions

export default authSlice.reducer