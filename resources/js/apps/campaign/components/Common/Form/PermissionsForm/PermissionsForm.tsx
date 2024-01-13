import { Action } from "@dnd/types";
import classNames from "classnames";
import { FC } from "react";
import { usePermissionsFormState } from "./PermissionsForm.state";
import { PermissionFormProps } from "./types";

const PermissionForm: FC<PermissionFormProps> = ({ entity, id, value, className, onChange }) => {
  const { input, override, selected, setOverride, selectAll, toggle, users } = usePermissionsFormState(
    entity,
    onChange,
    id,
    value
  )
  const actions: Action[] = [ 'view', 'edit', 'delete' ]

  return <div className={classNames("permissions", className)}>
    <div className="uk-margin">
      <p>Do you want to override the standard permissions per user?</p>
      <label className="uk-margin-right">
        <input className="uk-radio"
               type="radio"
               value={1}
               checked={override}
               onChange={(e) => setOverride(e.target.checked)} /> Yes
      </label>
      <label>
        <input className="uk-radio"
               type="radio"
               value={0}
               checked={!override}
               onChange={(e) => setOverride(!e.target.checked)} /> No
      </label>
    </div>
    {
      override && input !== null && users !== null ?
        <table className="uk-table">
          <thead>
          <tr>
            <th>User</th>
            {
              actions.map((action) => <th key={`action-${action}`}>
                <label style={{ textTransform: 'capitalize' }}>
                  <input className="uk-checkbox uk-margin-small-right"
                         type="checkbox"
                         checked={selected[action]}
                         onChange={() => selectAll(action)} /> {action}
                </label>
              </th>)
            }
          </tr>
          </thead>
          <tbody>
          {
            users.map((user) => <tr key={`user-${user.id}`}>
              <td style={{ textTransform: 'capitalize' }}>{user.name}</td>
              {actions.map((action) => <td key={`user-${user.id}-${action}`}>
                  <input type="checkbox"
                         className="uk-checkbox"
                         onChange={(e) => toggle(user.id, action, e.target.checked)}
                         checked={!!(
                           input[user.id]?.[action]
                         )} />
                </td>
              )}
            </tr>)
          }
          </tbody>
        </table>
        : null
    }
  </div>
}

export default PermissionForm