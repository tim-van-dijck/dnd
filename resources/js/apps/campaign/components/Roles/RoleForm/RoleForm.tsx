import { useParams } from "react-router"
import { useRoleFormState } from "./RoleForm.state";
import { useRoleFormUI } from "./RoleForm.ui";
import RoleFormView from "./RoleForm.view";

const RoleForm = () => {
  const { id: idAsString } = useParams()
  const id: number | undefined = typeof idAsString === 'string' ? parseInt(idAsString) : idAsString
  const { errors, input, permissions, save, selected, selectAll, update, updatePermission } = useRoleFormState(
    id)
  const { actions, can, redirect, title } = useRoleFormUI(input, id)

  return <RoleFormView state={{ errors, input, permissions, save, selected, selectAll, update, updatePermission }}
                       ui={{ actions, can, redirect, title }} />
}

export default RoleForm