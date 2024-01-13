import { Action } from "@dnd/types";
import { useNavigate } from "react-router-dom";
import { RoleInput } from "../../../../../types/roles";
import { useCampaignRepositories } from "../../../providers/CampaignRepositoryProvider";

export const useRoleFormUI = (role: RoleInput | null, id: number | undefined) => {
  const { AuthRepository } = useCampaignRepositories()
  const navigate = useNavigate()
  const actions: Action[] = [ 'view', 'create', 'edit', 'delete' ]

  return {
    actions,
    can: AuthRepository.can,
    redirect: () => navigate('/roles'),
    title: id ? `Edit ${role ? role.name : 'role'}` : 'Create role'
  }
}