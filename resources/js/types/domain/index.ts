import { Campaign } from "../campaigns";
import { Action } from "../permissions";
import { User } from "../users";


export interface AuthRepositoryInterface {
  user: User | null
  campaign: () => Promise<Campaign>
  loadUser: () => Promise<User>
  logout: () => Promise<string | void>
  can: (permission: Action, entity: string, id?: number | null) => boolean
  hasRole: (user, role) => boolean
}

export type TSharedRepositoryContext = {
  AuthRepository: AuthRepositoryInterface
}