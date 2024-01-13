import { useModals } from '../../../modals'
import { useEffect } from "react";
import { useUserRepository } from "../../../repositories/UserRepository";

export const useUserOverviewState = () => {
  const userRepository = useUserRepository()
  const { confirmDelete } = useModals()

  useEffect(() => void userRepository.load(), [])

  return {
    userRepository,
    destroy: (userId: number) => {
      confirmDelete(
        'user',
        () => userRepository.destroy(userId).then(() => userRepository.load())
      )
    }
  }
}
