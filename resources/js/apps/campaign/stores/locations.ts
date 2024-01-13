import { Location } from '@dnd/types'
import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";

type LocationState = {
  locations: PaginatedData<Location> | null
}

const initialState: LocationState = {
  locations: null
}

export const locationSlice = createSlice({
  name: 'locations',
  initialState,
  reducers: {
    setLocations: (state, action) => {
      state.locations = action.payload
    }
  }
})

export const { setLocations } = locationSlice.actions

export default locationSlice.reducer