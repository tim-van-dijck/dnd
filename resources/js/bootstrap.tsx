import { FC, ReactElement, StrictMode, useEffect } from "react";
import axios from "axios";
import { Provider } from "react-redux";
import UIkit from "uikit";
import Icons from "uikit/dist/js/uikit-icons";
import { SharedRepositoryProvider } from "./providers/SharedRepositoryProvider";
import { LoginProvider } from "./providers/UserProvider";

const Bootstrap: FC<{ children: ReactElement, store }> = ({ children, store }) => {
  useEffect(() => void UIkit.use(Icons), [])

  const token = document.querySelector('meta[name="token"]')?.getAttribute('content')
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

  return <StrictMode>
    <Provider store={store}>
      <SharedRepositoryProvider>
        <LoginProvider>
          {children}
        </LoginProvider>
      </SharedRepositoryProvider>
    </Provider>
  </StrictMode>
}

export default Bootstrap

