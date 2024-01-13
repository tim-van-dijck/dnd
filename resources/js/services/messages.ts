import { useSharedDispatch, useSharedSelector } from "@dnd/stores";
import { error, success } from "../stores/messages";


export const useMessageBus = () => {
  const dispatch = useSharedDispatch()
  const messages = useSharedSelector(state => state.messages.messages)

  return {
    messages,
    success: (message) => {
      dispatch(success(message))
    },
    error: (message) => {
      dispatch(error(message))
    }
  }
}