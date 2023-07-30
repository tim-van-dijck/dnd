import { Note } from "@dnd/types";
import { createSlice } from "@reduxjs/toolkit";
import { PaginatedData } from "../../../repositories/BaseRepository";

type NoteState = {
  notes: PaginatedData<Note> | null
}

const initialState: NoteState = {
  notes: null
}

export const noteSlice = createSlice({
  name: 'note',
  initialState,
  reducers: {
    setNotes: (state, action) => {
      state.notes = action.payload
    }
  }
})

export const { setNotes } = noteSlice.actions

export default noteSlice.reducer