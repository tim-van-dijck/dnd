import { JournalEntry } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";

type JournalState = {
  entries: PaginatedData<JournalEntry> | null
}

const initialState: JournalState = {
  entries: null
}

export const journalSlice = createSlice({
  name: 'journal',
  initialState,
  reducers: {
    setEntries: (state, action) => {
      state.entries = action.payload
    }
  }
})

export const { setEntries } = journalSlice.actions

export default journalSlice.reducer