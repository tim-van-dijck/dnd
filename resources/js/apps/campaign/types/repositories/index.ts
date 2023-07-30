import {
  Campaign,
  CampaignLog,
  CampaignUser,
  CampaignUserInput,
  EntityUserPermissionList,
  JournalEntry,
  JournalEntryInput,
  Location,
  LocationInput,
  Note,
  NoteInput,
  Quest,
  QuestInput
} from "@dnd/types";
import { PaginatedData } from "../../../../repositories/BaseRepository";
import { Permission, Role, RoleInput } from "../../../../types/roles";
import { BaseRepositoryInterface } from "../../../admin/repositories/types";

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
  locations: PaginatedData<Location> | null,
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
  users: PaginatedData<CampaignUser> | null
  load: () => Promise<PaginatedData<CampaignUser>>
  previous: () => Promise<PaginatedData<CampaignUser>> | null
  page: (number: number) => Promise<PaginatedData<CampaignUser>> | null,
  next: () => Promise<PaginatedData<CampaignUser>> | null
  find: (id: number) => Promise<CampaignUser>
  invite: (user: CampaignUserInput) => Promise<void>
  update: (id: number, user: CampaignUserInput) => Promise<void>
  destroy: (id: number) => Promise<void>
}