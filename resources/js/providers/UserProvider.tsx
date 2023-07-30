import { createContext, ReactNode, useEffect } from "react";
import { useAuthRepository } from "../repositories/AuthRepository";
import { useSharedRepositories } from "./SharedRepositoryProvider";
import { useDispatch } from "react-redux";
import { User } from "../types";
import { useSharedSelector } from "@dnd/stores";
import { setUser } from "../stores/auth";

export const UserContext = createContext<Partial<User>>({
  id: 0,
  email: '',
  permissions: []
})

export const LoginProvider = ({ children }: { children: ReactNode }) => {
  const dispatch = useDispatch()
  const authRepository = useAuthRepository()

  useEffect(
    () => void authRepository.loadUser()
      .then(({ id, email, permissions }) => dispatch(setUser({ id, email, permissions }))),
    []
  )

  if (authRepository.user) {
    return <UserContext.Provider value={authRepository.user}>{children}</UserContext.Provider>
  }

  return null
}
export const withUser = (ChildComponent) => {
  return (props) => (
    <UserContext.Consumer>{(user) => <ChildComponent {...props} user={user} />}</UserContext.Consumer>
  )
}