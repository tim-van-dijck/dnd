import { AuthRepositoryInterface } from '@dnd/types'
import { createContext, ReactNode, useContext } from 'react'
import { useAuthRepository } from '../../../repositories/AuthRepository'
import { useCampaignRepository } from '../repositories/CampaignRepository'
import { useCampaignUserRepository } from '../repositories/CampaignUserRepository'
import { useCharacterRepository } from '../repositories/CharacterRepository'
import { useJournalRepository } from '../repositories/JournalRepository'
import { useLocationRepository } from '../repositories/LocationRepository'
import { useNoteRepository } from '../repositories/NoteRepository'
import { usePermissionRepository } from '../repositories/PermissionRepository'
import { useQuestRepository } from '../repositories/QuestRepository'
import { useRoleRepository } from '../repositories/RoleRepository'
import { TCampaignRepositoryContext } from '../types'
import {
  CampaignRepositoryInterface,
  CampaignUserRepositoryInterface,
  JournalRepositoryInterface,
  LocationRepositoryInterface,
  NoteRepositoryInterface,
  PermissionRepositoryInterface,
  PlayerCharacterRepositoryInterface,
  QuestRepositoryInterface,
  RoleRepositoryInterface
} from '../types/repositories'

export const CampaignRepositoryContext = createContext<TCampaignRepositoryContext>({
  AuthRepository: {} as unknown as AuthRepositoryInterface,
  CampaignRepository: {} as unknown as CampaignRepositoryInterface,
  JournalRepository: {} as unknown as JournalRepositoryInterface,
  LocationRepository: {} as unknown as LocationRepositoryInterface,
  NoteRepository: {} as unknown as NoteRepositoryInterface,
  PermissionRepository: {} as unknown as PermissionRepositoryInterface,
  PlayerCharacterRepository: {} as unknown as PlayerCharacterRepositoryInterface,
  QuestRepository: {} as unknown as QuestRepositoryInterface,
  RoleRepository: {} as unknown as RoleRepositoryInterface,
  UserRepository: {} as unknown as CampaignUserRepositoryInterface
})

export const CampaignRepositoryProvider = ({ children }: { children: ReactNode }) => {
  const AuthRepository = useAuthRepository()
  const CampaignRepository = useCampaignRepository()
  const JournalRepository = useJournalRepository()
  const LocationRepository = useLocationRepository()
  const NoteRepository = useNoteRepository()
  const PermissionRepository = usePermissionRepository()
  const PlayerCharacterRepository = useCharacterRepository()
  const RoleRepository = useRoleRepository()
  const QuestRepository = useQuestRepository()
  const UserRepository = useCampaignUserRepository()

  const value = {
    AuthRepository,
    CampaignRepository,
    JournalRepository,
    LocationRepository,
    NoteRepository,
    PermissionRepository,
    PlayerCharacterRepository,
    QuestRepository,
    RoleRepository,
    UserRepository
  }

  return <CampaignRepositoryContext.Provider value={value}>{children}</CampaignRepositoryContext.Provider>
}
export const withCampaignRepositories = (ChildComponent) => {
  return (props) => (
    <CampaignRepositoryContext.Consumer>{(repositories) =>
      <ChildComponent {...props} repositories={repositories} />}
    </CampaignRepositoryContext.Consumer>
  )
}

export const useCampaignRepositories = () => useContext<TCampaignRepositoryContext>(CampaignRepositoryContext)