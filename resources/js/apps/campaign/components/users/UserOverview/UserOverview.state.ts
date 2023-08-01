import { CampaignUser, CampaignUserInput } from "@dnd/types";
import { useEffect, useState } from "react";
import UIkit from "uikit";
import { useModals } from "../../../../admin/modals";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";
import { useCampaignUserRepository } from "../../../repositories/CampaignUserRepository";

export const useUserOverview = () => {
  const { prompt } = useModals()
  const { AuthRepository } = useCampaignRepositories()
  const userRepository = useCampaignUserRepository()
  const [ input, setInput ] = useState<CampaignUser | null>(null)

  useEffect(() => void userRepository.load(), [])

  const invite = () => {
    setInput(null)
    UIkit.modal('#user-modal').show();
  }

  const edit = (user: CampaignUser) => {
    setInput(user)
    UIkit.modal('#user-modal').show();
  }

  const onFinish = () => {
    setInput(null)
    userRepository.load()
  }

  return {
    can: AuthRepository.can,
    me: AuthRepository.user,
    userRepository,
    invite,
    edit,
    input,
    onFinish,
    destroy: (userId) => {
      prompt(
        `Are you sure you want to revoke this user's access to the campaign?? Please write REVOKE to confirm`,
        {
          labels: {
            ok: 'Delete',
            cancel: 'cancel'
          }
        }, 'REVOKE',
        'Invalid input, delete cancelled.',
        () => userRepository.destroy(userId).then(() => userRepository.load())
      )
    }
  }
}

const emptyInput: CampaignUserInput = { email: '', role: '' }