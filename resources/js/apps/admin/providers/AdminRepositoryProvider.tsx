import { createContext, ReactNode, useContext } from "react";
import { useCampaignRepository } from "../repositories/CampaignRepository";
import { useRaceRepository } from "../repositories/RaceRepository";
import { useSpellRepository } from "../repositories/SpellRepository";
import { useUserRepository } from "../repositories/UserRepository";
import {
  CampaignRepositoryInterface,
  RaceRepositoryInterface,
  SpellRepositoryInterface,
  TAdminRepositoryContext,
  UserRepositoryInterface
} from "../repositories/types";

export const AdminRepositoryContext = createContext<TAdminRepositoryContext>({
  CampaignRepository: {} as unknown as CampaignRepositoryInterface,
  RaceRepository: {} as unknown as RaceRepositoryInterface,
  SpellRepository: {} as unknown as SpellRepositoryInterface,
  UserRepository: {} as unknown as UserRepositoryInterface,
})

export const AdminRepositoryProvider = ({ children }: { children: ReactNode }) => {
  const CampaignRepository = useCampaignRepository()
  const RaceRepository = useRaceRepository()
  const SpellRepository = useSpellRepository()
  const UserRepository = useUserRepository()

  const value = {
    CampaignRepository,
    RaceRepository,
    SpellRepository,
    UserRepository
  }

  return <AdminRepositoryContext.Provider value={value}>{children}</AdminRepositoryContext.Provider>
}
export const withAdminRepositories = (ChildComponent) => {
  return (props) => (
    <AdminRepositoryContext.Consumer>{(repositories) =>
      <ChildComponent {...props} repositories={repositories} />}
    </AdminRepositoryContext.Consumer>
  )
}

export const useAdminRepositories = () => useContext<TAdminRepositoryContext>(AdminRepositoryContext)