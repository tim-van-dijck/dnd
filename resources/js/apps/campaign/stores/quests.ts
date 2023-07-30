import { Quest } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";

type QuestState = {
  quests: PaginatedData<Quest> | null
}

const initialState: QuestState = {
  quests: null
}

export const questSlice = createSlice({
  name: 'quest',
  initialState,
  reducers: {
    setQuests: (state, action) => {
      state.quests = action.payload
    }
  }
})

export const { setQuests } = questSlice.actions

export default questSlice.reducer