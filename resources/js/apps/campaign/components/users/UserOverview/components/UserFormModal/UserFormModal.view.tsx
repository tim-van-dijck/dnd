import { FC } from "react";
import UIkit from "uikit";
import FormButtons from "../../../../../../../components/layout/form/components/FormButtons";
import InputSelect from "../../../../../../../components/layout/form/components/InputSelect";
import InputText from "../../../../../../../components/layout/form/components/InputText";
import { UserFormModalViewProps } from "./UserFormModal.types";

const UserFormModalView: FC<UserFormModalViewProps> = ({ state }) => {
  return <form id="user-form" onSubmit={state.save}>
    {
      state.user ? <p className="uk-text-large">{state.user.name}</p> :
        <InputText id="email"
                   name="email"
                   label="Email"
                   initialValue={state.input?.email}
                   errors={state.errors?.email}
                   onChange={(value) => state.update('email', value)}
                   required />
    }

    <InputSelect id="role"
                 name="role"
                 label="Role"
                 initialValue={state.input?.role}
                 options={state.roles}
                 errors={state.errors?.role}
                 onChange={(value) => state.update('role', value)}
                 required />
    <FormButtons cancel={() => {
      state.onFinish?.()
      UIkit.modal('#user-modal').hide()
    }} />
  </form>
}

export default UserFormModalView