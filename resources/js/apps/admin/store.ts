import { configureStore } from "@reduxjs/toolkit";
import { TypedUseSelectorHook, useDispatch, useSelector } from "react-redux";
import authReducer from '../../stores/auth'
import messageReducer from '../../stores/messages'
import spellReducer from '../../stores/spells'
import campaignReducer from './stores/campaigns'
import raceReducer from './stores/races'
import userReducer from './stores/users'

export const store = configureStore({
  reducer: {
    auth: authReducer,
    messages: messageReducer,
    campaigns: campaignReducer,
    races: raceReducer,
    spells: spellReducer,
    users: userReducer
  }
})

export type AdminState = ReturnType<typeof store.getState>

export type AdminDispatch = typeof store.dispatch

export const useAdminDispatch: () => AdminDispatch = useDispatch
export const useAdminSelector: TypedUseSelectorHook<AdminState> = useSelector