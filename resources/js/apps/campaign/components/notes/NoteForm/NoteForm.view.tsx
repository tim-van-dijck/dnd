import classNames from "classnames";
import { FC } from "react";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import InputBoolean from "../../../../../components/layout/form/components/InputBoolean";
import InputRichText from "../../../../../components/layout/form/components/InputRichText";
import InputText from "../../../../../components/layout/form/components/InputText";
import PermissionsForm from "../../common/form/PermissionsForm";
import { NoteFormViewProps } from "./types";

const NoteFormView: FC<NoteFormViewProps> = ({ state, ui }) => {
  return <div>
    <h1>{ui.title}</h1>
    <div className="uk-section uk-section-default">
      {state.input ?
        <form onSubmit={state.save}>
          {
            ui.can('edit', 'role') ? <ul data-uk-tab>
              <li className={classNames({ 'uk-active': ui.tab === 'details' })}>
                <a href="" onClick={() => ui.setTab('details')}>Details</a>
              </li>
              <li className={classNames({ 'uk-active': ui.tab === 'permissions' })}>
                <a href="" onClick={() => ui.setTab('permissions')}>Permissions</a>
              </li>
            </ul> : null
          }
          <div className={classNames({ 'uk-hidden': ui.tab !== 'details' })}>
            <InputText id="name"
                       name="name"
                       label="Name"
                       initialValue={state.input?.name}
                       errors={state.errors?.name}
                       onChange={(value) => state.update('name', value)}
                       required />
            <InputRichText id="content"
                           name="content"
                           label="Content"
                           initialValue={state.input?.content}
                           errors={state.errors?.content}
                           onChange={(value) => state.update('content', value)}
                           required />
            <InputBoolean id="private"
                          name="private"
                          label="Private"
                          initialValue={state.input?.private}
                          errors={state.errors?.private}
                          onChange={(value) => state.update('private', value)}
                          required />
          </div>
          <PermissionsForm
            className={classNames({ 'uk-hidden': ui.tab !== 'permissions' || !ui.can('edit', 'role') })}
            id={state.id}
            entity="note"
            value={state.input.permissions}
            onChange={(value) => state.update('permissions', value)} />
          <FormButtons cancel={ui.redirect} />
        </form> : <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>
      }
    </div>
  </div>
}

export default NoteFormView