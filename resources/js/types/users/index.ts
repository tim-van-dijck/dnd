export interface User {
  id: number
  email: string
  name: string
  active: boolean
  admin: boolean
  permissions?
}

export interface UserInput {
  email?: string
  name?: string
  active?: boolean
  admin?: boolean
}

export interface CampaignUser {
  id: number
  name: string
  email: string
  active: boolean
  admin: boolean
  role: string
  role_id: number
}

export interface CampaignUserInput {
  id?: number
  name?: string
  email: string
  role: string | number
}

export interface UserErrors {
  email?: string | string[]
  name?: string | string[]
  admin?: string | string[]
}

export interface CampaignUserErrors {
  email?: string | string[]
  name?: string | string[]
  admin?: string | string[]
  role?: string | string[]
}