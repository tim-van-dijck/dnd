import { createSlice } from "@reduxjs/toolkit";

type MessageState = {
  messages: Message[]
}

type Message = {
  id: number
  type: 'success' | 'danger',
  message: string
}

type MessageAction = {
  type: string
  payload: {
    message: string
    type?: 'success' | 'error'
  }
}

const initialState: MessageState = {
  messages: []
}

export const messageSlice = createSlice({
  name: 'messages',
  initialState,
  reducers: {
    success: (state, action: MessageAction): void => {
      const { payload } = action
      const success = getMessage('success', payload.message)
      state.messages = [ success, ...state.messages ]
    },
    error: (state, action: MessageAction): void => {
      const { payload } = action
      const error = getMessage('error', payload.message)
      state.messages = [ error, ...state.messages ]
    }
  }
})

export const { success, error } = messageSlice.actions

export default messageSlice.reducer

const getMessage = (type: 'success' | 'error', message: string): Message => (
  {
    message,
    type: type === 'error' ? 'danger' : type,
    id: new Date().getTime()
  }
)