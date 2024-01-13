import classNames from "classnames";
import { FC } from "react";
import FormButtons from "../../../../../../components/layout/form/components/FormButtons";
import PermissionsForm from "../PermissionsForm";
import FieldSelector from "./components/FieldSelector";
import { CampaignEntityFormViewProps } from "./types";

const CampaignEntityFormView: FC<CampaignEntityFormViewProps> = ({ state, ui }) => {
  if (state.input === null) {
    return <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin"></i></p>
  } else {
    return <form onSubmit={state.submit}>
      {
        ui.permissions && ui.can('edit', 'role') ? <ul data-uk-tab>
          <li className={classNames({ 'uk-active': ui.tab === 'details' })}>
            <a href="" onClick={() => ui.setTab('details')}>Details</a>
          </li>
          <li className={classNames({ 'uk-active': ui.tab === 'permissions' })}>
            <a href="" onClick={() => ui.setTab('permissions')}>Permissions</a>
          </li>
        </ul> : null
      }
      <div className={classNames({ 'uk-hidden': ui.tab !== 'details' })}>
        {
          ui.fields.map((field) => <FieldSelector key={field.name}
                                                  initialValue={state.input![field.name]}
                                                  errors={state.errors}
                                                  field={field}
                                                  update={state.update} />)
        }
      </div>
      <PermissionsForm
        className={classNames({ 'uk-hidden': ui.tab !== 'permissions' || !ui.can('edit', 'role') })}
        id={state.id}
        entity={state.entity}
        value={state.input.permissions}
        onChange={(value) => state.update('permissions', value)} />
      <FormButtons cancel={ui.redirect} />
    </form>
  }
}

export default CampaignEntityFormView