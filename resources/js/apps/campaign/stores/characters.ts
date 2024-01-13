import { Background, CharacterClass, PlayerCharacter, Race } from '@dnd/types'
import { createSlice } from '@reduxjs/toolkit'
import { PaginatedData } from '../../../repositories/BaseRepository'

type CharacterState = {
  backgrounds: Background[] | null
  characters: PaginatedData<PlayerCharacter> | null
  classes: Record<number, CharacterClass> | null
  npcs: PaginatedData<PlayerCharacter> | null
  races: Race[] | null
}

const initialState: CharacterState = {
  backgrounds: null,
  characters: null,
  classes: null,
  npcs: null,
  races: null,
}

export const characterSlice = createSlice({
  name: 'characters',
  initialState,
  reducers: {
    setBackgrounds: (state, action) => {
      state.backgrounds = action.payload
    },
    setCharacters: (state, action) => {
      state.characters = action.payload
    },
    setClasses: (state, action) => {
      state.classes = action.payload
    },
    setRaces: (state, action) => {
      state.races = action.payload
    }
  }
})

export const { setBackgrounds, setCharacters, setClasses, setRaces } = characterSlice.actions

export default characterSlice.reducer