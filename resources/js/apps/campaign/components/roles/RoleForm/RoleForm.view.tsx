import { Action } from "@dnd/types";
import { FC } from "react";
import FormButtons from "../../../../../components/layout/form/components/FormButtons";
import InputText from "../../../../../components/layout/form/components/InputText";
import { RoleFormViewProps } from "./RoleForm.types";

const RoleFormView: FC<RoleFormViewProps> = ({ state, ui }) => {
  return <div>
    <h1>{ui.title}</h1>
    <div className="uk-section uk-section-default">
      <div className="uk-container padded">
        {
          state.permissions !== null && state.input !== null ?
            <form onSubmit={state.save}>
              <InputText id="name"
                         name="name"
                         label="Name"
                         initialValue={state.input?.name}
                         errors={state.errors?.name}
                         onChange={(value) => state.update('name', value)}
                         required />
              <h2>Permissions</h2>
              {state.permissions !== null ?
                <table className="uk-table">
                  <thead>
                  <tr>
                    <th>Entity</th>
                    {
                      ui.actions.map((action) =>
                        <th key={`header-${action}`} style={{ textTransform: 'capitalize' }}>
                          <input type="checkbox"
                                 className="uk-checkbox uk-margin-small-right"
                                 onChange={() => state.selectAll(
                                   action as Action)}
                                 checked={state.selected[action]} />{action}
                        </th>
                      )
                    }
                  </tr>
                  </thead>
                  <tbody>
                  {
                    state.permissions.map((permission) =>
                      <tr key={permission.name}>
                        <td style={{ textTransform: "capitalize" }}>{permission.name}</td>
                        {
                          ui.actions.map((action) => <td key={`${permission.name}-${action}`}>
                            <input type="checkbox"
                                   className="uk-checkbox"
                                   checked={state.input!.permissions[permission.id][action]}
                                   onChange={(e) => state.updatePermission(permission.id, action, e.target.checked)} />
                          </td>)
                        }
                      </tr>
                    )
                  }
                  </tbody>
                </table> :
                <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin" /></p>}
              <FormButtons cancel={ui.redirect} />
            </form> : <p className="uk-text-center"><i className="fas fa-2x fa-sync fa-spin" /></p>
        }
      </div>
    </div>
  </div>
}

export default RoleFormView