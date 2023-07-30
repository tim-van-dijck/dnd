import { configureStore } from "@reduxjs/toolkit";
import { TypedUseSelectorHook, useDispatch, useSelector } from "react-redux";
import authReducer from '../../stores/auth'
import messageReducer from '../../stores/messages'
import spellReducer from '../../stores/spells'
import campaignReducer from './stores/campaign'
import journalReducer from './stores/journal'
import locationReducer from './stores/locations'
import noteReducer from './stores/notes'
import permissionReducer from './stores/permissions'
import questReducer from './stores/quests'
import roleReducer from './stores/roles'
import campaignUserReducer from './stores/users'

export const store = configureStore({
  reducer: {
    auth: authReducer,
    campaign: campaignReducer,
    journal: journalReducer,
    locations: locationReducer,
    messages: messageReducer,
    notes: noteReducer,
    quests: questReducer,
    permissions: permissionReducer,
    roles: roleReducer,
    spells: spellReducer,
    users: campaignUserReducer
  }
})

export type CampaignState = ReturnType<typeof store.getState>

export type CampaignDispatch = typeof store.dispatch

export const useCampaignDispatch: () => CampaignDispatch = useDispatch
export const useCampaignSelector: TypedUseSelectorHook<CampaignState> = useSelector