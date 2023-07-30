import {
  Campaign,
  CampaignInput,
  Language,
  Proficiency,
  Race,
  RaceInput,
  Spell,
  SpellInput,
  Trait,
  User,
  UserInput
} from "@dnd/types";
import { PaginatedData } from "../../../repositories/BaseRepository";

export type TAdminRepositoryContext = {
  CampaignRepository: CampaignRepositoryInterface
  RaceRepository: RaceRepositoryInterface
  SpellRepository: SpellRepositoryInterface
  UserRepository: UserRepositoryInterface
}

export interface BaseRepositoryInterface<Entity, EntityInput> {
  previous: () => Promise<PaginatedData<Entity>> | null
  page: (number: number) => Promise<PaginatedData<Entity>> | null,
  next: () => Promise<PaginatedData<Entity>> | null
  load: () => Promise<PaginatedData<Entity>>
  find: (id: number) => Promise<Entity>
  store: (model: EntityInput) => Promise<void>
  update: (id: number, model: EntityInput) => Promise<void>
  destroy: (id: number) => Promise<void>
}

export interface CampaignRepositoryInterface extends BaseRepositoryInterface<Campaign, CampaignInput> {
  campaigns: PaginatedData<Campaign> | null
  userCampaigns: Record<number, Campaign[]>
  loadForUser: (userId: number) => Promise<Campaign[]>
}

export interface RaceRepositoryInterface extends BaseRepositoryInterface<Race, RaceInput> {
  languages: Language[] | null
  proficiencies: Proficiency[] | null
  races: PaginatedData<Race> | null
  traits: Trait[] | null
  loadLanguages: () => Promise<void>
  loadProficiencies: () => Promise<void>
  loadTraits: () => Promise<void>
}

export interface SpellRepositoryInterface extends BaseRepositoryInterface<Spell, SpellInput> {
  spells: PaginatedData<Spell> | null
}

export interface UserRepositoryInterface extends BaseRepositoryInterface<User, UserInput> {
  users: PaginatedData<User> | null
  invite: (user: User) => Promise<void>
  resetPassword: (id: number) => Promise<void>
}