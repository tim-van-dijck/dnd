import { useEffect } from "react";
import { useModals } from "../../../../admin/modals";
import { useRoleRepository } from "../../../repositories/RoleRepository";

export const useRoleOverviewState = () => {
  const roleRepository = useRoleRepository()
  const { confirmDelete } = useModals()

  useEffect(() => void roleRepository.load(), [])

  const destroy = (roleId) => {
    confirmDelete('role', () => roleRepository.destroy(roleId).then(() => roleRepository.load()))
  }

  return {
    roles: roleRepository.roles,
    destroy
  }
}