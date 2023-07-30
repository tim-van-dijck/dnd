import { createSlice } from "@reduxjs/toolkit";
import { Language, Proficiency, Race, Trait } from "../../../types";
import { PaginatedData } from "../../../repositories/BaseRepository";

type RaceState = {
  languages: Language[] | null,
  proficiencies: Proficiency[] | null,
  races: PaginatedData<Race> | null,
  traits: Trait[] | null
}

const initialState: RaceState = {
  languages: null,
  proficiencies: null,
  races: null,
  traits: null
}

export const raceSlice = createSlice({
  name: 'races',
  initialState,
  reducers: {
    setLanguages: (state, action) => void (
      state.languages = action.payload
    ),
    setProficiencies: (state, action) => void (
      state.proficiencies = action.payload
    ),
    setRaces: (state, action) => void (
      state.races = action.payload
    ),
    setTraits: (state, action) => void (
      state.traits = action.payload
    ),
  }
})

export const { setLanguages, setProficiencies, setRaces, setTraits } = raceSlice.actions

export default raceSlice.reducer