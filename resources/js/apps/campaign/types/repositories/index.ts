import {
  Background,
  Campaign,
  CampaignLog,
  CampaignUser,
  CampaignUserInput,
  CharacterClass,
  EntityUserPermissionList,
  JournalEntry,
  JournalEntryInput,
  Location,
  LocationInput,
  Note,
  NoteInput,
  PlayerCharacter,
  PlayerCharacterInput,
  Quest,
  QuestInput,
  Race
} from '@dnd/types'
import { PaginatedData } from '../../../../repositories/BaseRepository'
import { Permission, Role, RoleInput } from '../../../../types/roles'
import { BaseRepositoryInterface } from '../../../admin/repositories/types'

export interface CampaignRepositoryInterface {
  campaign: Campaign | null
  logs: CampaignLog[]
  loadCampaign: () => Promise<void>
  loadLogs: () => Promise<void>
}

export interface NoteRepositoryInterface extends BaseRepositoryInterface<Note, NoteInput> {
  notes: PaginatedData<Note> | null
}

export interface QuestRepositoryInterface extends BaseRepositoryInterface<Quest, QuestInput> {
  quests: PaginatedData<Quest> | null
  toggleObjective: (questId: number, objectiveId: number, status) => Promise<void>
}

export interface JournalRepositoryInterface extends BaseRepositoryInterface<JournalEntry, JournalEntryInput> {
  entries: PaginatedData<JournalEntry> | null,
  sort: (ids: number[]) => void
}

export interface LocationRepositoryInterface extends BaseRepositoryInterface<Location, LocationInput> {
  locations: PaginatedData<Location> | null
}

export interface PlayerCharacterRepositoryInterface extends BaseRepositoryInterface<PlayerCharacter, PlayerCharacterInput> {
  backgrounds: Background[] | null
  characters: PaginatedData<PlayerCharacter> | null
  classes: Record<number, CharacterClass> | null
  races: Race[] | null
  npcs: PaginatedData<PlayerCharacter> | null

  loadBackgrounds: () => Promise<Background[]>
  loadClasses: () => Promise<Record<number, CharacterClass>>
  loadRaces: () => Promise<Race[]>
  store: (model: PlayerCharacterInput) => Promise<number>
  update: (id: number, model: PlayerCharacterInput) => Promise<number>
}

export interface PermissionRepositoryInterface {
  permissions: any
  fetch: (props: { entity: string, id?: number }) => Promise<EntityUserPermissionList | void>
}

export interface RoleRepositoryInterface extends BaseRepositoryInterface<Role, RoleInput> {
  roles: PaginatedData<Role> | null
  permissions: Permission[] | null
  loadPermissions: () => Promise<Permission[]>
}

export interface CampaignUserRepositoryInterface {
  users: CampaignUser[] | null
  load: () => Promise<CampaignUser[]>
  find: (id: number) => Promise<CampaignUser>
  invite: (user: CampaignUserInput) => Promise<void>
  update: (id: number, user: CampaignUserInput) => Promise<void>
  destroy: (id: number) => Promise<void>
}