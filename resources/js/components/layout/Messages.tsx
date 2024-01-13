import {createContext, FC, ReactElement, useState} from "react";

type TMessageContext = {
  messages: TMessage[]
  success: (message: string) => void
  error: (message: string) => void
} | null

type TMessage = {
  id?: number
  type: 'success' | 'danger',
  message: string
}

export const MessageContext = createContext<TMessageContext>(null)

export const MessagesProvider: FC<{ children: ReactElement }> = ({children}) => {
  const [messages, setMessages] = useState<TMessage[]>([])
  const addMessage = (type: 'success' | 'danger', message: string) => {
    const id = new Date().getTime()
    setMessages([...messages, {type, message, id}])
    setTimeout(() => {
      setMessages(messages.filter((m) => m.id !== id))
    }, 5000)
  }
  const success = (message: string) => addMessage('success', message)
  const error = (message: string) => addMessage('danger', message)

  return <MessageContext.Provider value={{messages, success, error}}>
    {
      messages.length === 0 ? null :
        <div id="messages">
          {
            messages.map((message) =>
              <div key={message.id} className={`message message-${message.type}`} uk-alert>
                <p>{message.message}</p>
              </div>)
          }
        </div>
    }
    {children}
  </MessageContext.Provider>
}

export const withMessages = (ChildComponent) =>
  (props) => (
    <MessageContext.Consumer>
      {(messageProps) => <ChildComponent {...props}
                                         messages={messageProps?.messages}
                                         success={messageProps?.success}
                                         error={messageProps?.error}/>}
    </MessageContext.Consumer>
  )