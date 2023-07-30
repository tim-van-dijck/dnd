import { createSlice } from "@reduxjs/toolkit";

export const userSlice = createSlice({
  name: 'users',
  initialState: {
    users: null
  },
  reducers: {
    setUsers: (state, action) => void (
      state.users = action.payload
    )
  }
})

export const { setUsers } = userSlice.actions

export default userSlice.reducer