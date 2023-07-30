import { CampaignUser, CampaignUserInput } from "@dnd/types";
import { useEffect, useState } from "react";
import UIkit from "uikit";
import { useModals } from "../../../../admin/modals";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";
import { useUserRepository } from "../../../repositories/UserRepository";

export const useUserOverview = () => {
  const { confirmDelete } = useModals()
  const { AuthRepository } = useCampaignRepositories()
  const userRepository = useUserRepository()
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
    userRepository,
    invite,
    edit,
    input,
    onFinish,
    destroy: (userId) => {
      confirmDelete(
        'user',
        () => userRepository.destroy(userId).then(() => userRepository.load())
      )
    }
  }
}

const emptyInput: CampaignUserInput = { email: '', role: '' }