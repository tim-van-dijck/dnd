import { createContext, ReactNode, useContext } from "react";
import { useAuthRepository } from "../repositories/AuthRepository";
import { AuthRepositoryInterface, TSharedRepositoryContext } from "../types";

export const SharedRepositoryContext = createContext<TSharedRepositoryContext>({
  AuthRepository: {} as unknown as AuthRepositoryInterface,
})

export const SharedRepositoryProvider = ({ children }: { children: ReactNode }) => {
  const AuthRepository = useAuthRepository()

  const value = {
    AuthRepository
  }

  return <SharedRepositoryContext.Provider value={value}>{children}</SharedRepositoryContext.Provider>
}
export const withSharedRepositories = (ChildComponent) => {
  return (props) => (
    <SharedRepositoryContext.Consumer>{(repositories) =>
      <ChildComponent {...props} repositories={repositories} />}
    </SharedRepositoryContext.Consumer>
  )
}

export const useSharedRepositories = () => useContext<TSharedRepositoryContext>(SharedRepositoryContext)