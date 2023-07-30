import { AuthRepositoryInterface } from "@dnd/types";
import {
  CampaignRepositoryInterface,
  CampaignUserRepositoryInterface,
  JournalRepositoryInterface,
  LocationRepositoryInterface,
  NoteRepositoryInterface,
  PermissionRepositoryInterface,
  QuestRepositoryInterface,
  RoleRepositoryInterface
} from "./repositories";

export type TCampaignRepositoryContext = {
  AuthRepository: AuthRepositoryInterface
  CampaignRepository: CampaignRepositoryInterface
  JournalRepository: JournalRepositoryInterface
  LocationRepository: LocationRepositoryInterface
  NoteRepository: NoteRepositoryInterface
  PermissionRepository: PermissionRepositoryInterface
  QuestRepository: QuestRepositoryInterface
  RoleRepository: RoleRepositoryInterface
  UserRepository: CampaignUserRepositoryInterface
}

export type CampaignEntity = 'character' | 'location' | 'inventory' | 'journal' | 'note' | 'quest' | 'user' | 'role'