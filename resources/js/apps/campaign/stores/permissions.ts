import { EntityPermissions } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";

type CampaignPermissionState = {
  permissions: {
    [entity: string]: EntityPermissions
  }
}

const initialState: CampaignPermissionState = {
  permissions: {}
}

export const permissionSlice = createSlice({
  name: 'campaignPermissions',
  initialState,
  reducers: {
    setPermissions: (state, action) => {
      state.permissions = action.payload
    }
  }
})

export const { setPermissions } = permissionSlice.actions

export default permissionSlice.reducer