import { configureStore } from "@reduxjs/toolkit";
import authReducer from './auth'
import messageReducer from './messages'
import { TypedUseSelectorHook, useDispatch, useSelector } from "react-redux";

export const store = configureStore({
  reducer: {
    auth: authReducer,
    messages: messageReducer
  }
})

export type SharedState = ReturnType<typeof store.getState>

export type SharedDispatch = typeof store.dispatch

export const useSharedDispatch: () => SharedDispatch = useDispatch
export const useSharedSelector: TypedUseSelectorHook<SharedState> = useSelector