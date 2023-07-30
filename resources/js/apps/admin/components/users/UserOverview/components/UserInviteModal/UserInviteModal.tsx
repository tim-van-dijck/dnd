import InputBoolean from "../../../../../../../components/layout/form/components/InputBoolean/InputBoolean";
import InputEmail from "../../../../../../../components/layout/form/components/InputEmail";
import FormButtons from "../../../../../../../components/layout/form/components/FormButtons";
import { useInviteModalState } from "./UserInviteModal.state";
import { FC } from "react";
import { UserInviteModalProps } from "./types";

const UserInviteModal: FC<UserInviteModalProps> = ({ onInvite }) => {
  const { cancel, errors, open, user, submit, onUpdate } = useInviteModalState(onInvite)
  return <>
    <button className="uk-button uk-button-primary" onClick={(e) => {
      e.preventDefault()
      open()
    }}><i className="fas fa-plus" /> Invite user
    </button>
    <div id="user-modal" data-uk-modal="container: false;">
      <div className="uk-modal-dialog uk-modal-body">
        <form className="uk-form-stacked" onSubmit={submit}>
          <InputEmail id="email"
                      name="email"
                      label="Email"
                      required
                      initialValue={user.email}
                      onChange={(value) => onUpdate('email', value)} />
          <InputBoolean id="admin"
                        name="admin"
                        label="Administrator"
                        errors={errors?.admin}
                        initialValue={user.admin}
                        onChange={(value) => onUpdate('admin', value)} />
          <FormButtons saveText="Invite" cancel={cancel} />
        </form>
      </div>
    </div>
  </>
}

export default UserInviteModal