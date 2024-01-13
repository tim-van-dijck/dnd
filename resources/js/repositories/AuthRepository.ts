import { useSharedDispatch, useSharedSelector } from '@dnd/stores'
import axios, { AxiosResponse } from 'axios'
import { setCampaign } from '../apps/campaign/stores/campaign'
import { CampaignEntity } from '../apps/campaign/types'
import { setUser } from '../stores/auth'
import { Action, AuthRepositoryInterface, Campaign, User } from '../types'

export const useAuthRepository = (): AuthRepositoryInterface => {
  const user: User | null = useSharedSelector(state => state.auth.user)
  const campaign = useSharedSelector(state => state.auth.campaign)
  const dispatch = useSharedDispatch()

  return {
    user,
    campaign: (): Promise<Campaign> => {
      if (campaign !== null) return Promise.resolve(campaign)
      return axios.get('/api/campaign').then((response: AxiosResponse<{ data: Campaign }>) => {
        const campaign = response.data.data
        dispatch(setCampaign(campaign))
        return campaign
      })
    },
    loadUser: (): Promise<User> => {
      if (user !== null) return Promise.resolve(user)

      return axios.get('/api/me')
        .then((response: AxiosResponse<{ data: User }>) => {
          const user = response.data.data
          dispatch(setUser(user))
          return user
        })
        .catch((error) => {
          if ([ 401, 403, 404 ].includes(error.response.status)) {
            window.location.href = '/'
          }
          return {} as unknown as User
        })
    },
    logout: (): Promise<string | void> => axios.post('/logout').then(() => document.location.href = '/'),
    can: (permission: Action, entity: CampaignEntity | string, id = null): boolean => {
      if (!user?.permissions?.hasOwnProperty(entity)) return false

      const permissions = user?.permissions[entity]
      if (id && permissions.exceptions?.[id]?.[permission] != null) return !!permissions.exceptions?.[id]?.[permission]

      return !!permissions?.[permission]
    },
    hasRole: (user: User, role: string | number): boolean => (
      user?.roles || []
    ).filter(item => item.id === role || item.name === role).length > 0
  }
}