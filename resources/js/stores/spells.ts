import { createSlice } from "@reduxjs/toolkit";


export const spellSlice = createSlice({
  name: 'spells',
  initialState: {
    spells: null
  },
  reducers: {
    setSpells: (state, action) => void (
      state.spells = action.payload
    )
  }
})

export const { setSpells } = spellSlice.actions

export default spellSlice.reducer