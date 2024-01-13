import { Campaign, User } from "@dnd/types";
import { useEffect, useState } from "react";
import { useModals } from "../../../modals";
import { useCampaignRepository } from "../../../repositories/CampaignRepository";
import { useUserRepository } from "../../../repositories/UserRepository";

export const useUserDetailState = (id?: number) => {
  const userRepository = useUserRepository()
  const campaignRepository = useCampaignRepository()
  const modals = useModals()

  const [ user, setUser ] = useState<User | null>(null)
  const [ campaigns, setCampaigns ] = useState<Campaign[] | null>(null)

  useEffect(() => {
    if (id) {
      userRepository.find(id).then((user) => setUser(user))
      campaignRepository.loadForUser(id).then(() => setCampaigns(campaigns))
    }
  }, [ id ])

  const resetPassword = () => {
    if (user) {
      modals.confirm(
        `Are you sure you want to send a password reset link to ${user.email}?`,
        '',
        () => userRepository.resetPassword(user.id!)
      )
    }
  }

  return { user, campaigns, resetPassword }
}