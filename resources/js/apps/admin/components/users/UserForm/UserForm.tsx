import InputText from "../../../../../components/layout/form/components/InputText";
import InputEmail from "../../../../../components/layout/form/components/InputEmail";
import InputBoolean from "../../../../../components/layout/form/components/InputBoolean/InputBoolean";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import { useParams } from "react-router";
import { useUserFormState } from "./UserForm.state";
import { useNavigate } from "react-router-dom";

const UserForm = () => {
  const navigate = useNavigate()
  const { id } = useParams() as { id: number | undefined }
  const { user, errors, submit, onUpdate } = useUserFormState(id)

  const title = id ? `Edit ${user ? user.name : 'user'}` : 'Add user'

  return (
    <div>
      <h1>{title}</h1>
      <div className="uk-section uk-section-default">
        <div className="uk-container padded">
          {
            user ?
              <form className="uk-form-stacked" onSubmit={submit}>
                <InputText id="name"
                           name="name"
                           label="Name"
                           initialValue={user.name}
                           errors={errors?.name}
                           onChange={(value) => onUpdate('name', value)} />
                <InputEmail id="email"
                            name="email"
                            label="E-mail"
                            initialValue={user.email}
                            errors={errors?.email}
                            onChange={(value) => onUpdate('email', value)} />
                <InputBoolean id="admin"
                              name="admin"
                              label="Administrator"
                              initialValue={user.admin}
                              onChange={(value) => onUpdate('admin', value)} />
                <InputBoolean id="active"
                              name="active"
                              label="Active"
                              initialValue={user.active}
                              onChange={(value) => onUpdate('active', value)} />
                <FormButtons cancel={() => navigate('/users')} />
              </form>
              :
              <p className="uk-text-center">
                <i className="fas fa-2x fa-sync fa-spin"></i>
              </p>
          }
        </div>
      </div>
    </div>
  )
}

export default UserForm